<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'tagline' => 'nullable|string|max:200',
            'contact_email' => 'required|email|max:150',
            'primary_color' => 'required|string|max:16',
            'default_qr_size' => 'required|integer|min:150|max:1000',
            'default_paper_size' => 'required|in:a4,a5,letter',
            'enable_geo_tracking' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        Setting::set('app_name', $validated['app_name'], 'string');
        Setting::set('tagline', $validated['tagline'] ?? '', 'string');
        Setting::set('contact_email', $validated['contact_email'], 'string');
        Setting::set('primary_color', $validated['primary_color'], 'string', 'design');
        Setting::set('default_qr_size', (int) $validated['default_qr_size'], 'integer', 'qr');
        Setting::set('default_paper_size', $validated['default_paper_size'], 'string', 'document');
        Setting::set('enable_geo_tracking', $request->boolean('enable_geo_tracking'), 'boolean', 'analytics');

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath, 'string', 'design');
        }

        ActivityLog::log(
            action: 'settings_updated',
            resourceType: 'Setting',
            details: ['app_name' => $validated['app_name']]
        );

        return back()->with('success', 'Application settings saved successfully.');
    }
}
