<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'service_id',
        'bundle_id',
        'promo_id',
        'customer_name',
        'phone',
        'address',
        'fabric_type',
        'weight_kg',
        'payment_method',
        'pickup_date',
        'pickup_time',
        'distance_km',
        'pickup_fee',
        'subtotal',
        'discount',
        'total_price',
        'status',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        // 'pickup_time' => 'timestamp', // Time casting can be tricky, relying on string usually safer for time only
        'weight_kg' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'pickup_fee' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function orderTrackings()
    {
        return $this->hasMany(OrderTracking::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
