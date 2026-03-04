<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'points',
        'status',
        'notes',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    /**
     * Kurir memiliki banyak order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Apakah kurir sedang bertugas
     */
    public function isOnDuty(): bool
    {
        return $this->status === 'on_duty';
    }

    /**
     * Label status dalam Bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'on_duty' ? 'Sedang Ditugaskan' : 'Idle';
    }

    /**
     * Warna badge status
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status === 'on_duty' ? 'yellow' : 'green';
    }
}
