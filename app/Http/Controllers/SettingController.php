<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

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
                'school_name' => 'LIGHT ACADEMY MODEL SCHOOL',
                'currency' => 'LRD',
                'exchange_rate' => 190, // default rate
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
            'school_name'     => 'required|string|max:255',
            'school_email'    => 'nullable|email|max:255',
            'school_phone'    => 'nullable|string|max:255',
            'school_address'  => 'nullable|string',

            'currency'        => 'required|in:LRD,USD',

            // ✅ FIX: exchange rate validation added
            'exchange_rate'   => 'nullable|numeric|min:0',

            'receipt_prefix'  => 'nullable|string|max:50',
            'system_name'     => 'nullable|string|max:255',
            'logo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $setting = Setting::first();

        if (!$setting) {
            $setting = new Setting();
        }

        /**
         * LOGO UPLOAD
         */
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('settings', 'public');
            $setting->logo = $logo;
        }

        /**
         * CORE SETTINGS
         */
        $setting->school_name    = $request->school_name;
        $setting->school_email   = $request->school_email;
        $setting->school_phone   = $request->school_phone;
        $setting->school_address = $request->school_address;
        $setting->currency       = $request->currency;

        /**
         * ✅ FIX: SAVE EXCHANGE RATE PROPERLY
         * - If USD selected → use input value
         * - If LRD selected → keep existing or reset default
         */
        if ($request->currency === 'USD') {
            $setting->exchange_rate = $request->exchange_rate ?? $setting->exchange_rate ?? 190;
        } else {
            $setting->exchange_rate = $setting->exchange_rate ?? 190;
        }

        $setting->receipt_prefix = $request->receipt_prefix;
        $setting->system_name    = $request->system_name;

        $setting->save();

        return back()->with('success', 'Settings updated successfully');
    }
}