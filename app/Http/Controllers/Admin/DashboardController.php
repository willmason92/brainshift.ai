<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostcodeMap;
use App\Models\Material;
use App\Scopes\PostTypeScope;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //
    public function index()
    {

        $pcs = PostcodeMap::where('region_id', 1)->limit(20);
        $pcs2 = PostcodeMap::where('region_id', 2)->limit(10);

        return Inertia::render('Admin/Dashboard', [
            'selected_postcodes' => $pcs->get(),
            'available_postcodes' => $pcs2->get(),
            'projects' => Post::withoutGlobalScope(PostTypeScope::class)
                ->with('postMeta', 'postType', 'author')->get(),
            'materials' => Material::all(),
        ]);
    }
}
