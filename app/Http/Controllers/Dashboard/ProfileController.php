<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function profile()
    {
        return view('dashboard.pages.profile');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . admin()->id,
            'username' => 'required|string|max:255|unique:admins,username,' . admin()->id,
        ]);

        admin()->update($data);

        return back()->with('success', __('Profile updated successfully'));

    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'old_password' => 'required|string',
        ]);
        if (!Hash::check($data['old_password'], admin()->password)) {
            return back()->withErrors(['old_password' => __('Old password does not match')]);
        }

        admin()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', __('Password updated successfully'));
    }

    public function logout()
    {
        adminAuth()->logout();
        return redirect()->route('dashboard.login')->with('success', __('You have been logged out.'));
    }
}
