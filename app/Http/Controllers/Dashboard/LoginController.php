<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginView()
    {
        if (adminAuth()->check())
            return redirect()->route('dashboard.dashboard');


        return view('dashboard.pages.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $remember = $request->boolean('remember');

        $loginField = filter_var($credentials['email_username'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $attemptData = [
            $loginField => $credentials['email_username'],
            'password' => $credentials['password'],
        ];

        if (!adminAuth()->attempt($attemptData, $remember)) {
            return back()->withInput()->with('error', __('These credentials do not match our records.'));
        }

        $request->session()->regenerate();
        return redirect()->route('dashboard.dashboard')->with('success', __('You are now logged in.'));
    }
}
