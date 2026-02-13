<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Stylist;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'users_count' => User::count(),
            'stylists_count' => Stylist::count(),
            'admins_count' => Admin::count(),
        ];

        return view('dashboard.pages.dashboard', compact('statistics'));
    }
}
