<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\NavCategories;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    //

    public function index() {
        $categories = NavCategories::whereHas('children')->orderBy('order')->with('children')->get();
        // dd([Auth::user()->getPermissionsViaRoles()->pluck('name'), $categories]);
        return view('dashboard', [
            'categories' => $categories,
        ]);
    }
}
