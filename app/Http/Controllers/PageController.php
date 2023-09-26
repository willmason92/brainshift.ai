<?php

namespace App\Http\Controllers;

use Facades\App\Models\Settings;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $slug
     * @return \Inertia\Response
     */
    public function view($slug)
    {
        //get "slug" and load from settings
        $slug = preg_replace(['/[^a-z0-9 \-_]/', '/[ ]/'], ['', '-'], strtolower($slug));

        //load from settings
        $content = Settings::get("static_page.$slug");

        //if good
        if (! empty($content)) {
            //return page content and template
            return Inertia::render('Page', [
                'content' => $content,
            ]);
        } else {
            abort(404);
        }
    }
}
