<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function edit()
    {
        $settings = Setting::firstOrCreate([]);
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_address' => 'required|string|max:500',
            'clinic_phone' => 'required|string|max:20',
            'clinic_email' => 'required|email|max:255',
            'business_hours' => 'required|string|max:500',
            'appointment_interval' => 'required|integer|min:5|max:120',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'about_text' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ]);

        $settings = Setting::firstOrCreate([]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo) {
                Storage::delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('settings');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if ($settings->favicon) {
                Storage::delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('settings');
        }

        $settings->update($validated);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }
}