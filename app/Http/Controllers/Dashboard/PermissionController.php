<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.permissions');
    }
}
