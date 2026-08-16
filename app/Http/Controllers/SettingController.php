<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create([
                'school_name'   => 'LIGHT ACADEMY MODEL SCHOOL',
                'currency'      => 'LRD',
                'exchange_rate' => 190,
            ]);
        }

        return view('settings.index', compact('setting'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'school_name'            => 'required|string|max:255',
            'school_email'           => 'nullable|email|max:255',
            'school_phone'           => 'nullable|string|max:255',
            'school_address'         => 'nullable|string',
            'currency'               => 'required|in:LRD,USD',
            'exchange_rate'          => 'nullable|numeric|min:0',
            'receipt_prefix'         => 'nullable|string|max:50',
            'system_name'            => 'nullable|string|max:255',
            'logo'                   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'authorized_signature'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'registrar_signature'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $setting = Setting::firstOrNew();

        /**
         * FILE UPLOADS
         */
        if ($request->hasFile('logo')) {
            $this->deleteIfExists($setting->logo);
            $setting->logo = $request->file('logo')->store('settings/logos', 'public');
        }

        if ($request->hasFile('authorized_signature')) {
            $this->deleteIfExists($setting->authorized_signature);
            $setting->authorized_signature = $request->file('authorized_signature')->store('settings/signatures', 'public');
        }

        if ($request->hasFile('registrar_signature')) {
            $this->deleteIfExists($setting->registrar_signature);
            $setting->registrar_signature = $request->file('registrar_signature')->store('settings/signatures', 'public');
        }

        /**
         * CORE SETTINGS
         */
        $setting->school_name    = $request->school_name;
        $setting->school_email   = $request->school_email;
        $setting->school_phone   = $request->school_phone;
        $setting->school_address = $request->school_address;
        $setting->currency       = $request->currency;
        $setting->receipt_prefix = $request->receipt_prefix;
        $setting->system_name    = $request->system_name;

        /**
         * EXCHANGE RATE LOGIC
         */
        if ($request->currency === 'USD') {
            $setting->exchange_rate = $request->exchange_rate ?? $setting->exchange_rate ?? 190;
        } else {
            $setting->exchange_rate = $setting->exchange_rate ?? 190;
        }

        $setting->save();

        return back()->with('success', 'Settings updated successfully');
    }

    /**
     * Helper: Delete a file from public storage if it exists
     */
    private function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}