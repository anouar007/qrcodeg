<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
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

        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }

        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($credentials['login']);
        $passwordInput = $credentials['password'];
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginField, $loginInput)->first();

        if ($user && Hash::check($passwordInput, $user->password)) {
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

        // Fall back to Client authentication (Email as login, Portal Access Code or password as password)
        $client = Client::where('email', $loginInput)
            ->orWhere('access_code', strtoupper($loginInput))
            ->first();

        $clientPasswordMatches = $client && (
            (! empty($client->access_code) && strcasecmp(trim($passwordInput), trim($client->access_code)) === 0)
            || (! empty($client->password) && Hash::check($passwordInput, $client->password))
        );

        if ($client && $clientPasswordMatches) {
            if (! $client->isActive()) {
                return back()->withInput($request->only('login'))
                    ->withErrors(['login' => 'Your client account is currently inactive. Please contact support.']);
            }

            Auth::guard('client')->login($client, $request->boolean('remember'));
            $request->session()->regenerate();

            $client->update(['last_login_at' => now()]);

            ActivityLog::log(
                action: 'client_login',
                resourceType: 'Client',
                resourceId: $client->id,
                details: ['login_method' => 'unified', 'email' => $client->email],
                client: $client
            );

            return redirect()->intended(route('client.dashboard'))->with('success', "Welcome back, {$client->name}!");
        }

        return back()->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'The provided credentials do not match our records.']);
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
