<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSetting;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = LandingPageSetting::first() ?? new LandingPageSetting();
        
        // Fetch footer settings
        $footerSettings = [
            'footer_company_description' => Setting::get('footer_company_description', 'Mitra layanan laundry premium Anda. Cepat, bersih, dan harum - setiap saat.'),
            'footer_email'               => Setting::get('footer_email', 'support@viplaundry.com'),
            'footer_phone'               => Setting::get('footer_phone', '+62 812-3456-7890'),
            'footer_address'             => Setting::get('footer_address', 'Jakarta, Indonesia'),
        ];

        return view('admin.settings.index', compact('setting', 'footerSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'laundry_address'            => 'required|string|max:500',
            'laundry_latitude'           => 'required|numeric|between:-90,90',
            'laundry_longitude'          => 'required|numeric|between:-180,180',
            'footer_company_description' => 'nullable|string|max:1000',
            'footer_email'               => 'nullable|email|max:255',
            'footer_phone'               => 'nullable|string|max:50',
            'footer_address'             => 'nullable|string|max:255',
        ]);

        // Save maps/location coordinates
        $setting = LandingPageSetting::first();
        if ($setting) {
            $setting->update($request->only('laundry_address', 'laundry_latitude', 'laundry_longitude'));
        } else {
            LandingPageSetting::create($request->only('laundry_address', 'laundry_latitude', 'laundry_longitude'));
        }

        // Save dynamic footer settings
        $footerKeys = ['footer_company_description', 'footer_email', 'footer_phone', 'footer_address'];
        foreach ($footerKeys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key), 'landing_page');
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
