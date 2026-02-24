<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = LandingPageSetting::first() ?? new LandingPageSetting();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'laundry_address'   => 'required|string|max:500',
            'laundry_latitude'  => 'required|numeric|between:-90,90',
            'laundry_longitude' => 'required|numeric|between:-180,180',
        ]);

        $setting = LandingPageSetting::first();
        if ($setting) {
            $setting->update($request->only('laundry_address', 'laundry_latitude', 'laundry_longitude'));
        } else {
            LandingPageSetting::create($request->only('laundry_address', 'laundry_latitude', 'laundry_longitude'));
        }

        return back()->with('success', 'Settings berhasil disimpan!');
    }
}
