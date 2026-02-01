<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageSetting extends Model
{
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_cta_primary',
        'hero_cta_secondary',
        'how_it_works_title',
        'how_it_works_subtitle',
        'services_title',
        'services_subtitle',
        'why_choose_title',
        'why_choose_subtitle',
        'cta_section_title',
        'cta_section_text',
        'cta_button_text',
        'footer_description',
        'contact_email',
        'contact_phone',
        'contact_address',
    ];

    /**
     * Get the landing page settings or create default
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([]);
        }
        
        return $settings;
    }
}
