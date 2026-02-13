<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.roles');
    }

    public function adminRoles()
    {
        return view('dashboard.pages.admin_roles');
    }
}
