<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageExperts\ExpertRequest;
use App\Models\Expert;
use App\Models\ExpertSpecialities;
use App\Models\ExpertType;
use App\Models\File;
use App\Models\Location;
use App\Models\Request;
use App\Models\RequestShareMetrics;
use App\Models\RequestStatusMetrics;
use App\Models\Sector;
use App\Models\SectorUserExpert;
use App\Models\ShedSolution;
use App\Models\ShedSolutionMetrics;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ExpertManagementController extends Controller
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
     * Page to list all expert details
     *
     * @return Response
     */
    public function index()
    {
        $experts = Expert::withTrashed()
            ->whereNull('user_id')
            ->get();

        return Inertia::render('Admin/ExpertManagement/Index', [
            'data' => $experts,
        ]);
    }

    /**
     * Page to display/edit the specific expert
     *
     * @param $expertId
     * @return Response
     */
    public function detail($expertId = null)
    {
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

        if ($expertId) {
            $expert = Expert::withTrashed()->find($expertId);
            $contactAllowed = $expert->contact_by_email;
        } else {
            $expert = null;
            $contactAllowed = null;
        }

        return Inertia::render('Admin/ExpertManagement/Detail', [
            'data' => $expert,
            'contactAllowed' => $contactAllowed,
            'sectors' => Sector::all(),
            'fileUploads' => $flash['file-uploads'],
            'fileRemoved' => $flash['file-removed'],
            'expert_types' => ExpertType::all()
        ]);
    }

    /**
     * Method to update the Expert model
     *
     * @param ExpertRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function store(ExpertRequest $request, $id = null)
    {
        $validated = $request->validated();

        if (!isset($validated['nationwide'])) {
            $validated['nationwide'] = false;
        }
        $expert = Expert::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'id' => $id,
                'company_name' => $validated['company_name'],
                'location_id' => null,
                'nationwide' => $validated['nationwide'],
                'description' => $validated['description'],
                'expert_type_id' => $validated['expert_type'],
                'expert_url' => $validated['expert_url'],
            ]
        );

        $location = Location::updateOrCreate(
            [
                'entity_id' => $expert->id,
                'entity_type' => $expert::class,
            ],
            array_merge([
                'entity_id' => $expert->id,
                'entity_type' => $expert::class,
                'name' => $validated['company_name'],
                'sizeradius' => empty($validated['nationwide']) ? $validated['radius'] : null,
                'sizeradiustype' => empty($validated['nationwide']) ? $validated['radiusType'] : null,
            ], json_decode($validated['location'], true))
        );

        $expert->location_id = $location->id;
        $expert->save();

        //handle logo
        $path = pathinfo($validated['company_logo']);
        if ($path['dirname'] === 'upload') {
            //move uploaded
            $file = File::moveUploadedFile($path['basename'], File::FILE_IMAGE, 'experts/company-logo');
            $file->save();
            $fId = null;
            //unlink old if applicable
            if (!empty($expert->expertLogo)) {
                $path = "/storage/{$expert->expertLogo->path}/{$expert->expertLogo->filename}";
                if (file_exists(public_path() . $path)) {
                    unlink(public_path() . $path);
                }
                $fId = $expert->logo;
            }
            //reassign new logo
            $expert->logo = $file->id;
            $expert->save();
            if (!empty($fId)) {
                File::where('id', $fId)->first()->delete();
            }
        }

        //sectors
        $expert->sectors()->sync($validated['sectors']);
        if ($request->firstTime) {
            return redirect()->back();
        }

        return redirect()->route('admin.manage-experts.index')->with('message', [
            'type' => 'success',
            'msg' => 'Your changes have been successfully saved',
        ]);
    }

    /**
     * Method to permanently delete the expert and associated data
     *
     * @param $expertId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function permDeleteExpert($expertId)
    {
        $expert = Expert::where('id', $expertId)
            ->whereDoesntHave('user')
            ->first();

        $validator = Validator::make(['id' => $expertId], [
            'id' => 'required|exists:experts,id',
        ]);
        if ($validator->fails() || empty($expert)) {
            $errors = $validator->errors();
            if (empty($expert)) {
                $errors->add('id', 'The requested expert could not be found.');
            }
            return redirect()->back()->withErrors($errors);
        }

        DB::transaction(function () use ($expert, $expertId) {
            // Invalidate posts, solutions and requests
            ShedSolution::where('user_id', $expertId)->update(['user_id' => null]);
            Request::where('installer_id', $expertId)->update(['installer_id' => null]);
            // Invalidate metrics
            RequestShareMetrics::where('installer_id', $expertId)->update(['installer_id' => null]);
            RequestStatusMetrics::where('user_id', $expertId)->update(['user_id' => null]);
            //Expert-specific data
            ShedSolutionMetrics::where('user_id', $expertId)->update(['user_id' => null]);
            ExpertSpecialities::where('expert_id', $expertId)->forceDelete();
            SectorUserExpert::where('expert_id', $expertId)->update(['expert_id' => null]);
            // User delete
            $expert->location()->forceDelete();
            $expert->forceDelete();
        });
        $type = 'success';
        $msg = 'Expert and associated data has been deleted';

        return redirect()->route('admin.manage-experts.index')->with('message', [
            'type' => $type,
            'msg' => $msg,
        ]);
    }
}
