<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageSetting extends Model
{
    protected $fillable = [
        'laundry_address',
        'laundry_latitude',
        'laundry_longitude',
    ];

    protected $casts = [
        'laundry_latitude' => 'float',
        'laundry_longitude' => 'float',
    ];
}
