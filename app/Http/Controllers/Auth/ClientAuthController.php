<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function showAccess()
    {
        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }

        return view('auth.client_access');
    }

    public function verifyAccessCode(Request $request)
    {
        $request->validate([
            'access_code' => 'required|string|max:64',
        ]);

        $code = trim(strtoupper($request->access_code));

        // Find matching client by client access code, or QR code access code
        $client = Client::where('access_code', $code)->first();
        $qr = null;

        if (! $client) {
            $qr = QrCode::where('access_code', $code)->first();
            if ($qr && $qr->client_id) {
                $client = $qr->client;
            }
        }

        if (! $client && ! $qr) {
            return back()->withInput()->withErrors(['access_code' => 'The provided access code is not recognized. Please verify the code printed with your QR.']);
        }

        // Store access code in session for subsequent password verification or initialization
        session(['pending_access_code' => $code]);

        $target = $client ?? $qr;

        if (! $target->is_password_set) {
            // First-time access: Prompt to set password
            return redirect()->route('client.password.setup')
                ->with('info', 'First-time access detected. Please set up a secure password to protect your account.');
        }

        // Password already exists: Prompt for password
        return redirect()->route('client.password.enter');
    }

    public function showPasswordEnter()
    {
        $code = session('pending_access_code');
        if (! $code) {
            return redirect()->route('client.access');
        }

        return view('auth.client_password_enter', compact('code'));
    }

    public function loginWithPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $code = session('pending_access_code');
        if (! $code) {
            return redirect()->route('client.access');
        }

        $client = Client::where('access_code', $code)->first();
        $qr = null;

        if (! $client) {
            $qr = QrCode::where('access_code', $code)->first();
            if ($qr && $qr->client_id) {
                $client = $qr->client;
            }
        }

        $target = $client ?? $qr;

        if (! $target || ! Hash::check($request->password, $target->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
        }

        // If target is a QR without an existing client, create or associate a lightweight client
        if (! $client && $qr) {
            $client = Client::create([
                'company_name' => $qr->name,
                'contact_name' => 'QR Owner',
                'email' => 'qr_'.strtolower(str_replace('-', '', $qr->public_code)).'@client.local',
                'status' => 'active',
                'access_code' => $qr->access_code,
                'password' => $qr->password,
                'is_password_set' => true,
            ]);
            $qr->update(['client_id' => $client->id]);
        }

        if (! $client->isActive()) {
            return redirect()->route('client.access')->with('error', 'Your client account is currently inactive.');
        }

        Auth::guard('client')->login($client);
        $request->session()->regenerate();
        session()->forget('pending_access_code');

        $client->update(['last_login_at' => now()]);

        ActivityLog::log(
            action: 'client_login',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['access_code' => $code],
            client: $client
        );

        return redirect()->route('client.dashboard')->with('success', 'Logged in successfully!');
    }

    public function showPasswordSetup()
    {
        $code = session('pending_access_code');
        if (! $code) {
            return redirect()->route('client.access');
        }

        return view('auth.client_password_setup', compact('code'));
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $code = session('pending_access_code');
        if (! $code) {
            return redirect()->route('client.access');
        }

        $client = Client::where('access_code', $code)->first();
        $qr = null;

        if (! $client) {
            $qr = QrCode::where('access_code', $code)->first();
            if ($qr && $qr->client_id) {
                $client = $qr->client;
            }
        }

        $hashed = Hash::make($request->password);

        if ($client) {
            $client->update([
                'password' => $hashed,
                'is_password_set' => true,
                'last_login_at' => now(),
            ]);
            if ($qr) {
                $qr->update(['password' => $hashed, 'is_password_set' => true]);
            }
        } elseif ($qr) {
            $qr->update(['password' => $hashed, 'is_password_set' => true]);
            $client = Client::create([
                'company_name' => $qr->name,
                'contact_name' => 'QR Owner',
                'email' => 'qr_'.strtolower(str_replace('-', '', $qr->public_code)).'@client.local',
                'status' => 'active',
                'access_code' => $qr->access_code,
                'password' => $hashed,
                'is_password_set' => true,
                'last_login_at' => now(),
            ]);
            $qr->update(['client_id' => $client->id]);
        }

        Auth::guard('client')->login($client);
        $request->session()->regenerate();
        session()->forget('pending_access_code');

        ActivityLog::log(
            action: 'password_created',
            resourceType: 'Client',
            resourceId: $client->id,
            details: ['first_time_setup' => true],
            client: $client
        );

        return redirect()->route('client.dashboard')->with('success', 'Password configured successfully! Welcome to your QR Management Portal.');
    }

    public function logout(Request $request)
    {
        $client = Auth::guard('client')->user();
        if ($client) {
            ActivityLog::log(
                action: 'client_logout',
                resourceType: 'Client',
                resourceId: $client->id,
                client: $client
            );
        }

        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.access')->with('info', 'You have been logged out from the client portal.');
    }
}
