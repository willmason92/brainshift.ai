<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageRegions\RegionRequest;
use App\Models\Region;
use App\Models\PostcodeMap;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RegionManagementController extends Controller
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * RegionManagementController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Page to list all region details
     *
     * @return Response
     */
    public function index()
    {
        $regions = Region::with('user')->get();

        return Inertia::render('Admin/RegionManagement/Index', [
            'data' => $regions,
        ]);
    }

    /**
     * Page to display/edit the specific region
     *
     * @param $regionId
     * @return Response
     */
    public function detail($regionId)
    {
        $pcs = PostcodeMap::where('region_id', $regionId)->limit(5);
        $pcs2 = PostcodeMap::whereNull('region_id')->limit(10);

        return Inertia::render('Admin/RegionManagement/Detail', [
            'data' => Region::find($regionId),
            'selected_postcodes' => $pcs->get(),
            'available_postcodes' => $pcs2->get(),
        ]);
    }

    public function add()
    {
        $pcs2 = PostcodeMap::whereNull('region_id')->limit(10);

        return Inertia::render('Admin/RegionManagement/Detail', [
            'new' => true,
            'available_postcodes' => $pcs2->get(),
        ]);
    }

    /**
     * Method to add or update a region & assigned postcodes
     *
     * @param RegionRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function addOrUpdateRegion(RegionRequest $request, $id = null)
    {
        $validated = $request->validated();

        if ($id !== null) {
            $removedPostcodes = array_diff($validated['oldPostcodes'], $validated['postcodes']);
            if ($validated['oldPostcodes'] !== $validated['postcodes']) {
                PostcodeMap::whereIn('id', $removedPostcodes)->update(['region_id' => null]);
            }
        }

        $region = Region::updateOrCreate(
            ['id' => $id],
            [
                'id' => $id,
                'region_name' => $validated['name'],
            ]
        );

        PostcodeMap::whereIn('id', $validated['postcodes'])
            ->update(['region_id' => $region->id]);

        return redirect()->route('admin.manage-regions.index')->with('message', [
            'type' => 'success',
            'msg' => 'The region has been saved successfully',
        ]);
    }

    /**
     * Method to permanently delete the region and associated data
     *
     * @param $regionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function permDeleteRegion($id)
    {
        $region = Region::find($id);

        if ($region) {
            PostcodeMap::where('region_id', $id)->update(['region_id' => null]);
            $region->forceDelete();
            $type = 'success';
            $msg = 'Region has been deleted and postcodes unassigned';
        } else {
            $type = 'error';
            $msg = 'Region has not been deleted';
        }

        return redirect()->route('admin.manage-regions.index')->with('message', [
            'type' => $type,
            'msg' => $msg,
        ]);
    }
}
