<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FarmerManagement\UpdateFarmerRequest;
use App\Models\Location;
use App\Models\Post;
use App\Models\Request;
use App\Models\RequestShareMetrics;
use App\Models\RequestStatusMetrics;
use App\Models\ShedSolution;
use App\Models\ShedSolutionMetrics;
use App\Models\User;
use App\Models\Sector;
use App\Models\Speciality;
use App\Rules\SectionRoleCheck;
use App\Rules\FarmerRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class FarmerManagementController extends Controller
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * FarmerManagementController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Page to list all farmers details
     *
     * @return Response
     */
    public function index()
    {
        $farmers = User::withTrashed()
            ->role('Farmer')
            ->select('id', 'email', 'name', 'phone')
            ->get();

        return Inertia::render('Admin/FarmerManagement/Index', [
            'data' => $farmers,
        ]);
    }

    /**
     * Page to display/edit the specific farmer
     *
     * @param $farmerId
     * @return Response
     */
    public function detail($farmerId)
    {
        $farmer = User::role('Farmer')
            ->select('id', 'email', 'name', 'phone')
            ->find($farmerId);

        $availableSectors = Sector::all();

        return Inertia::render('Admin/FarmerManagement/Detail', [
            'data' => $farmer,
            'sectors' => Sector::all(),
            'specialities' => Speciality::all(),
            'contactAllowed' => $farmer->contact_by_email
        ]);
    }

    public function editFarmerDetails(UpdateFarmerRequest $request, $id)
    {

        $user = Auth::user();
        $farmer = User::findOrFail($id);

        $validated = $request->validated();

        $farm = Location::updateOrCreate(
            [
                'entity_id' => $farmer->id,
                'entity_type' => $farmer::class,
            ],
            array_merge([
                'entity_id' => $farmer->id,
                'entity_type' => $farmer::class,
                'name' => $validated['farmName'],
            ], json_decode($validated['location'], true))
        );

        $farmer->sectors()->sync($validated['sectors']);

        return redirect()->route('admin.farmers.index')->with('message', [
            'type' => 'success',
            'msg' => 'Farmer details updated successfully'
        ]);
    }

    /**
     * Method to permanently delete the farmer and datate/invalidate associated data
     *
     * @param $farmerId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function permDeleteFarmer($farmerId)
    {
        $farmer = User::withTrashed()
            ->role('Farmer')
            ->find($farmerId);

        $validator = Validator::make(['id' => $farmerId], [
            'id' => ['required', 'exists:users,id',  new SectionRoleCheck('Farmer', $farmerId)],
        ]);
        if ($validator->fails() || empty($farmer)) {
            $errors = $validator->errors();
            if (empty($expert)) {
                $errors->add('id', 'The requested farmer could not be found.');
            }
            return redirect()->back()->withErrors($errors);
        }

        DB::transaction(function () use ($farmer, $farmerId) {
            // Invalidate posts, solutions and requests
            Post::where('author', $farmerId)->update(['author' => null]);
            ShedSolution::where('user_id', $farmerId)->update(['user_id' => null]);
            Request::where('user_id', $farmerId)->update(['user_id' => null]);
            // Invalidate metrics
            RequestShareMetrics::where('user_id', $farmerId)->update(['user_id' => null]);
            RequestStatusMetrics::where('user_id', $farmerId)->update(['user_id' => null]);
            ShedSolutionMetrics::where('user_id', $farmerId)->update(['user_id' => null]);
            // User delete
            $farmer->location()->forceDelete();
            $farmer->forceDelete();
        });

        $type = 'success';
        $msg = 'Farmer and associated data has been deleted';

        return redirect()->route('admin.manage-farmers.index')->with('message', [
            'type' => $type,
            'msg' => $msg,
        ]);
    }
}
