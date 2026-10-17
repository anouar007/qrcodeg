<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginField, $credentials['login'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'The provided credentials do not match our records.']);
        }

        if ($user->status !== 'active') {
            return back()->withInput($request->only('login'))
                ->withErrors(['login' => 'Your account is currently inactive. Please contact an administrator.']);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $user->update(['last_login_at' => now()]);

        ActivityLog::log(
            action: 'user_login',
            resourceType: 'User',
            resourceId: $user->id,
            details: ['login_field' => $loginField, 'email' => $user->email],
            user: $user
        );

        return redirect()->intended(route('admin.dashboard'))->with('success', "Welcome back, {$user->first_name}!");
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            ActivityLog::log(
                action: 'user_logout',
                resourceType: 'User',
                resourceId: $user->id,
                user: $user
            );
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out.');
    }
}
