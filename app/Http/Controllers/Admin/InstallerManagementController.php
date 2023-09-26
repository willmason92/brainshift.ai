<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstallerManagement\UpdateInstallerRequest;
use App\Models\Expert;
use App\Models\ExpertType;
use App\Models\File;
use App\Models\Location;
use App\Models\Post;
use App\Models\Request;
use App\Models\RequestShareMetrics;
use App\Models\RequestStatusMetrics;
use App\Models\Sector;
use App\Models\ShedSolution;
use App\Models\ShedSolutionMetrics;
use App\Models\Speciality;
use App\Models\User;
use App\Rules\SectionRoleCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class InstallerManagementController extends Controller
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * RequestController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Page to list all Installers, two views active users/soft-deleted users
     *
     * @return Response
     */
    public function index()
    {
        $installers = User::with('expert', 'location')
            ->role('Installer')
            ->select('id', 'email', 'name', 'phone')
            ->get();

        return Inertia::render('Admin/InstallerManagement/Index', [
            'data' => $installers,
        ]);
    }

    /**
     * Page to list the specific Installers details and toggle to edit
     *
     * @param $installerId
     * @return Response
     */
    public function detail($installerId)
    {
        $installer = User::with('expert')
            ->role('Installer')
            ->select('id', 'email', 'name', 'phone')
            ->find($installerId);

        $flash = session('flash', []);
        if (!isset($flash['file-uploads'])) {
            $flash['file-uploads'] = [
                'gallery' => [
                    'id' => null,
                    'url' => null,
                    'delete_key' => null,
                ],
            ];
        }
        if (!isset($flash['file-removed'])) {
            $flash['file-removed'] = [];
        }

        return Inertia::render('Admin/InstallerManagement/Detail', [
            'data' => $installer,
            'contactAllowed' => $installer->contact_by_email,
            'sectors' => Sector::all(),
            'specialities' => Speciality::all(),
            'fileUploads' => $flash['file-uploads'],
            'fileRemoved' => $flash['file-removed'],
        ]);
    }

    public function editInstallerDetails(UpdateInstallerRequest $request, $id)
    {
        $installerUser = User::find($id);

        $validated = $request->validated();

        $msg = 'Your Installer Profile has been updated and will be visible in Find an Expert.';
        //create location for upsert
        $location = Location::updateOrCreate(
            [
                'entity_id' => $installerUser->id,
                'entity_type' => $installerUser::class,
            ],
            array_merge([
                'entity_id' => $installerUser->id,
                'entity_type' => $installerUser::class,
                'name' => $validated['company_name'],
                'sizeradius' => empty($validated['nationwide']) ? $validated['radius'] : null,
                'sizeradiustype' => empty($validated['nationwide']) ? $validated['radiusType'] : null,
            ], json_decode($validated['location'], true))
        );

        //create expert for upsert
        $installer = Expert::updateOrCreate(
            [
                'user_id' => $id,
            ],
            [
                'user_id' => $id,
                'company_name' => $validated['company_name'],
                'location_id' => 1,
                'nationwide' => $validated['nationwide'],
                'description' => $validated['description'],
                'expert_type_id' => ExpertType::where('name', 'Installers')->pluck('id')->first(),
            ]
        );
        //handle logo
        $path = pathinfo($validated['company_logo']);
        if ($path['dirname'] === 'upload') {
            //move uploaded
            $file = File::moveUploadedFile($path['basename'], File::FILE_IMAGE, 'experts/company-logo');
            $file->save();
            $fId = null;
            //unlink old if applicable
            if (!empty($installer->expertLogo)) {
                $path = "/storage/{$installer->expertLogo->path}/{$installer->expertLogo->filename}";
                if (file_exists(public_path() . $path)) {
                    unlink(public_path() . $path);
                }
                $fId = $installer->logo;
            }
            //reassign new logo
            $installer->logo = $file->id;
            $installer->save();
            if (!empty($fId)) {
                File::where('id', $fId)->first()->delete();
            }
        }

        //specialities
        $installer->specialities()->sync($validated['specialities']);
        //sectors
        $installer->sectors()->sync($validated['sectors']);
        if ($request->firstTime) {
            return redirect()->back();
        }


        return redirect()->route('admin.manage-installers.index')->with('message', [
            'type' => 'success',
            'msg' => 'Installer details updated successfully'
        ]);
    }

    /**
     * Permanently delete Installer as well as associated data
     *
     * @param $installerId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function permDeleteInstaller($installerId)
    {
        $user = Auth::user();
        $installer = User::withTrashed()
            ->role('Installer')
            ->find($installerId);

        $validator = Validator::make(['id' => $installerId], [
            'id' => ['required', 'exists:users,id',  new SectionRoleCheck('Installer', $installerId)],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if (empty($expert)) {
                $errors->add('id', 'The requested installer could not be found.');
            }
            return redirect()->back()->withErrors($errors);
        }

        DB::transaction(function () use ($installer, $installerId) {
            // Invalidate posts, solutions and requests
            ShedSolution::where('user_id', $installerId)->update(['user_id' => null]);
            // Invalidate metrics
            RequestShareMetrics::where('installer_id', $installer->expert->id)->update(['installer_id' => null]);
            RequestStatusMetrics::where('user_id', $installerId)->update(['user_id' => null]);
            ShedSolutionMetrics::where('user_id', $installerId)->update(['user_id' => null]);

            Request::where('installer_id', $installer->expert->id)->update(['installer_id' => null]);
            Post::where('author', $installer->id)->forceDelete();

            // User delete
            $installer->expert()->forceDelete();
            $installer->location()->forceDelete();
            $installer->forceDelete();
        });
        $type = 'success';
        $msg = 'Installer and associated data has been deleted';

        return redirect()->route('admin.manage-installers.index')->with('message', [
            'type' => $type,
            'msg' => $msg,
        ]);
    }
}
