<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSetting;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    // Hero Section
    public function editHero()
    {
        $settings = LandingPageSetting::getSettings();
        return view('admin.landing.hero', compact('settings'));
    }

    public function updateHero(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'hero_cta_primary' => 'required|string|max:100',
            'hero_cta_secondary' => 'required|string|max:100',
        ]);

        $settings = LandingPageSetting::first();
        
        if ($settings) {
            $settings->update($validated);
        } else {
            LandingPageSetting::create($validated);
        }

        return redirect()->route('admin.landing.hero.edit')
            ->with('success', 'Hero section berhasil diperbarui!');
    }

    // How It Works Section
    public function editHowItWorks()
    {
        $settings = LandingPageSetting::getSettings();
        return view('admin.landing.how-it-works', compact('settings'));
    }

    public function updateHowItWorks(Request $request)
    {
        $validated = $request->validate([
            'how_it_works_title' => 'required|string|max:255',
            'how_it_works_subtitle' => 'required|string',
        ]);

        LandingPageSetting::first()->update($validated);

        return redirect()->route('admin.landing.how-it-works.edit')
            ->with('success', 'How It Works section berhasil diperbarui!');
    }

    // Services Section
    public function editServices()
    {
        $settings = LandingPageSetting::getSettings();
        return view('admin.landing.services', compact('settings'));
    }

    public function updateServices(Request $request)
    {
        $validated = $request->validate([
            'services_title' => 'required|string|max:255',
            'services_subtitle' => 'required|string',
        ]);

        LandingPageSetting::first()->update($validated);

        return redirect()->route('admin.landing.services.edit')
            ->with('success', 'Services section berhasil diperbarui!');
    }

    // Why Choose Us Section
    public function editWhyChoose()
    {
        $settings = LandingPageSetting::getSettings();
        return view('admin.landing.why-choose', compact('settings'));
    }

    public function updateWhyChoose(Request $request)
    {
        $validated = $request->validate([
            'why_choose_title' => 'required|string|max:255',
            'why_choose_subtitle' => 'required|string',
        ]);

        LandingPageSetting::first()->update($validated);

        return redirect()->route('admin.landing.why-choose.edit')
            ->with('success', 'Why Choose Us section berhasil diperbarui!');
    }

    // CTA Section
    public function editCta()
    {
        $settings = LandingPageSetting::getSettings();
        return view('admin.landing.cta', compact('settings'));
    }

    public function updateCta(Request $request)
    {
        $validated = $request->validate([
            'cta_section_title' => 'required|string|max:255',
            'cta_section_text' => 'required|string',
            'cta_button_text' => 'required|string|max:100',
        ]);

        LandingPageSetting::first()->update($validated);

        return redirect()->route('admin.landing.cta.edit')
            ->with('success', 'CTA section berhasil diperbarui!');
    }

    // Footer Section
    public function editFooter()
    {
        $settings = LandingPageSetting::getSettings();
        return view('admin.landing.footer', compact('settings'));
    }

    public function updateFooter(Request $request)
    {
        $validated = $request->validate([
            'footer_description' => 'required|string',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_address' => 'required|string|max:255',
        ]);

        LandingPageSetting::first()->update($validated);

        return redirect()->route('admin.landing.footer.edit')
            ->with('success', 'Footer section berhasil diperbarui!');
    }
}
