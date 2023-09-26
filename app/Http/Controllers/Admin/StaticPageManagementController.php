<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaticPages\UpdateStaticPage;
use App\Models\Settings as StaticPageSettings;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StaticPageManagementController extends Controller
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * StaticPageManagementController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Page to list all page details
     *
     * @return Response
     */
    public function index()
    {
        $pages = StaticPageSettings::orderBy('id','desc')->get();

        return Inertia::render('Admin/StaticPageManagement/Index', [
            'data' => $pages,
        ]);
    }
    /**
     * Page to display/edit the specific post
     *
     * @param $pageId
     * @return Response
     */
    public function edit($pageId)
    {
        $page = StaticPageSettings::find($pageId);

        return Inertia::render('Admin/StaticPageManagement/Detail', [
            'data' => $page,
        ]);
    }

    /**
     * Method to update the static page value field
     *
     * @param UpdateStaticPage $request
     * @param $pageId
     * @return RedirectResponse
     */
    public function updatePage(UpdateStaticPage $request, $pageId)
    {
        $validated = $request->validated();

        $page = StaticPageSettings::updateOrCreate(
            ['id' => $pageId],
            [
                'value' => $validated['value']
            ]
        );

        return redirect()->back()->with('message', [
            'type' => 'success',
            'msg' => 'The page has been saved successfully',
        ]);
    }
}
