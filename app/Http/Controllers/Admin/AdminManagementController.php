<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminManagement\UpdateAdminRequest;
use App\Models\Post;
use App\Models\Request;
use App\Models\RequestShareMetrics;
use App\Models\RequestStatusMetrics;
use App\Models\ShedSolution;
use App\Models\ShedSolutionMetrics;
use App\Models\User;
use App\Models\Region;
use App\Rules\SectionRoleCheck;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class AdminManagementController extends Controller
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
     * List all saved Admins
     *
     * @return Response
     */
    public function index()
    {
        $admins = User::role('Admin')->with('regions')->get();

        return Inertia::render('Admin/ManageAdmins/Index', [
            'data' => $admins,
        ]);
    }

    /**
     * Page for Super Admins to view the Admin data based on their UserID
     *
     * @param $adminId
     * @return Response
     */
    public function detail($adminId = null)
    {
        if ($adminId) {
            $admin = User::role('Admin')->with('regions')->whereId($adminId)->firstOrFail();
            $regions = Region::where('user_id', $adminId)
                ->orWhereNull('user_id')
                ->get();
        } else {
            $admin = null;
            $regions = Region::whereNull('user_id')->get();
        }

        return Inertia::render('Admin/ManageAdmins/Detail', [
            'admin' => $admin,
            'regions' =>  $regions,
            'edit' => false,
        ]);
    }

    /**
     * Method to add or edit an admin and their assigned region
     *
     * @param UpdateAdminRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function AddOrUpdateAdmin(UpdateAdminRequest $request, $id = null)
    {
        $user = User::find($id);

        if (!is_null($id)) {
            if (!$user && !$user->hasRole('Admin')) {
                abort(403);
            }
        }

        $validated = $request->validated();

        $admin = User::updateOrCreate(
            ['id' => $id],
            [
                'id' => $id,
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]
        );
        // Assign Admin role to user
        $admin->assignRole('Admin');

        Region::where('user_id', $id)->update(['user_id' => null]);
        Region::updateOrCreate(
            [
                'id' => $validated['region'],
            ], [
                'id' => $validated['region'],
                'user_id' => $id = $id ?? $admin->id,
            ]
        );

        return redirect()->route('admin.manage-admins.index')->with('message', [
            'type' => 'success',
            'msg' => 'The admin has been updated successfully',
        ]);
    }

    /**
     * Method to permanently delete an Admin
     *
     * @return RedirectResponse
     */
    public function permDeleteAdmin($adminId)
    {
        $admin = User::find($adminId);
        $validator = Validator::make(['id' => $adminId], [
            'id' => ['required', 'exists:users,id',  new SectionRoleCheck('Admin', $adminId)],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        DB::transaction(function () use ($admin, $adminId) {
            // Invalidate posts, solutions and requests
            Post::where('author', $adminId)->update(['author' => null]);
            ShedSolution::where('user_id', $adminId)->update(['user_id' => null]);
            Request::where('user_id', $adminId)->update(['user_id' => null]);
            // Invalidate metrics
            RequestShareMetrics::where('user_id', $adminId)->update(['user_id' => null]);
            RequestStatusMetrics::where('user_id', $adminId)->update(['user_id' => null]);
            ShedSolutionMetrics::where('user_id', $adminId)->update(['user_id' => null]);
            // Region
            Region::where('user_id', $adminId)->update(['user_id' => null]);
            // User delete
            $admin->location()->forceDelete();
            $admin->forceDelete();
        });
        $type = 'success';
        $msg = 'Admin has been deleted';


        return redirect()->route('admin.manage-admins.index')->with('message', [
            'type' => $type,
            'msg' => $msg,
        ]);
    }
}
