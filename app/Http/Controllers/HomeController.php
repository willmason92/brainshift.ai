<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
//        $u = Auth::user();
//        if ($u) {
//            dump($u);
//        }

        return Inertia::render('Home', []);
    }
}
