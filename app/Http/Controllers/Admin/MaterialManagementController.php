<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MaterialManagement\UpdateOrCreateMaterialRequest;
use App\Http\Requests\Admin\RequestManagementRequest;
use App\Models\Expert;
use App\Models\File;
use App\Models\Material;
use App\Models\Colour;
use App\Models\Request as InstallerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MaterialManagementController extends AdminController
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

    public function index()
    {
        $user = Auth::user();

        $materials = Material::with('colour')->get();

        return Inertia::render('Admin/MaterialManagement/Index', [
            'data' => $materials,
        ]);
    }

    /**
     * Page to edit the materials within the app
     *
     * @param $id
     * @return \Inertia\Response
     */
    public function edit($id = null)
    {
        if ($id) {
            $material = Material::with('colour')->whereId($id)->firstOrFail();
        } else {
            $material = null;
        }

        $productLines = [
            [
                'id' => 0,
                'text' => 'FarmScape',
            ],
            [
                'id' => 1,
                'text' => 'P3'
            ],
            [
                'id' => 2,
                'text' => 'P6'
            ],
        ];

        $flash = session('flash', []);
        if (! isset($flash['file-uploads'])) {
            $flash['file-uploads'] = [
                'gallery' => [
                    'id' => null,
                    'url' => null,
                    'delete_key' => null,
                ],
            ];
        }
        if (! isset($flash['file-removed'])) {
            $flash['file-removed'] = [];
        }

        return Inertia::render('Admin/MaterialManagement/Edit', [
            'material' => $material,
            'colours' => Colour::all(),
            'productLines' => $productLines,
            'fileUploads' => $flash['file-uploads'],
            'fileRemoved' => $flash['file-removed'],
        ]);
    }

    /**
     * Method to create or update a material model
     *
     * @param UpdateOrCreateMaterialRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function createOrUpdateMaterial(UpdateOrCreateMaterialRequest $request, $id = null)
    {
        $validated = $request->validated();

        //handle image
        $path = pathinfo($validated['image']);
            //move uploaded
            $file = File::moveUploadedFile($path['basename'], File::FILE_IMAGE, 'materials');
            $file->save();
            $fId = null;
            //reassign new logo
            $validated['image'] = $file->id;
            if (! empty($fId)) {
                File::where('id', $fId)->first()->delete();
            }

        $material = Material::updateOrCreate(
            [
                'id' => $id,
            ], array_merge([
                'id' => $id,
                'name' => $validated['name'],
                'product_line' => $validated['productLine'],
                'image' => $validated['image'],
                'colour' => $validated['colour'],
                'shop_url' => $validated['url'],
            ])
        );

        if ($id = null) {
            $msg = "Image created successfully";
        } else {
            $msg = "Material updated successfully";
        }

        return redirect()->route('admin.manage-materials.index')->with('message', [
            'type' => 'success',
            'msg' => $msg,
        ]);
    }

    /**
     * Method to permanently delete a material
     *
     * @param $id
     * @return RedirectResponse
     */
    public function permDeleteMaterial($id)
    {
        $material = Material::find($id);

        if ($material) {
            $material->forceDelete();
            $type = 'success';
            $msg = 'Material has been deleted';
        } else {
            $type = 'error';
            $msg = 'Material has not been deleted';
        }

        return redirect()->route('admin.manage-materials.index')->with('message', [
            'type' => $type,
            'msg' => $msg,
        ]);
    }
}
