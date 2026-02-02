<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $settings = LandingPageSetting::first();
        
        // Create default settings if none exist
        if (!$settings) {
            $settings = LandingPageSetting::create([
                'laundry_latitude' => -0.1185067,
                'laundry_longitude' => 100.566124,
                'laundry_address' => 'Toko Obat "Sejahtera"',
            ]);
        }
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'laundry_address' => 'required|string',
            'laundry_latitude' => 'nullable|numeric|between:-90,90',
            'laundry_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $settings = LandingPageSetting::first();
        
        if (!$settings) {
            $settings = new LandingPageSetting();
        }
        
        $settings->laundry_address = $request->laundry_address;
        $settings->laundry_latitude = $request->laundry_latitude;
        $settings->laundry_longitude = $request->laundry_longitude;
        $settings->save();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan lokasi laundry berhasil diperbarui!');
    }
}
