<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Promo;
use App\Models\Service;
use App\Models\LandingPageSetting;

class PricingService
{
    /**
     * Menghitung total harga, diskon, dan biaya pickup untuk pesanan layanan laundry.
     * 
     * @param string $orderType 'service' atau 'bundle'
     * @param int|null $serviceId
     * @param int|null $bundleId
     * @param float|null $weightKg
     * @param float|null $distanceKm (Opsional, dari client)
     * @param string|null $promoCode
     * @param float|null $latitude
     * @param float|null $longitude
     * @param bool $isOffline
     * @return array
     */
    public function calculate(
        string $orderType,
        ?int $serviceId = null,
        ?int $bundleId = null,
        ?float $weightKg = 0,
        ?float $distanceKm = 0,
        ?string $promoCode = null,
        ?float $latitude = null,
        ?float $longitude = null,
        bool $isOffline = false
    ): array {
        // --- 1. Hitung Subtotal ---
        $subtotal = 0;
        $resolvedServiceId = null;
        $resolvedBundleId = null;
        $effectiveWeight = max((float)$weightKg, 0);

        if ($orderType === 'service') {
            $service = Service::findOrFail($serviceId);
            // Pada order online (customer), berat biasanya 0 di awal dan dihitung ulang nanti
            // Pada order offline/admin, berat sudah diinput di awal
            $subtotal = $service->price_per_kg * $effectiveWeight;
            $resolvedServiceId = $service->id;
        } else {
            $bundle = Bundle::findOrFail($bundleId);
            $subtotal = $bundle->price;
            $resolvedBundleId = $bundle->id;
        }

        // --- 2. Pickup Fee ---
        $pickupFee = 0;
        $effectiveDistanceKm = (float) $distanceKm;

        if (!$isOffline) {
            // Server-side distance calculation to prevent Client-Side Trust manipulation
            if (!empty($latitude) && !empty($longitude)) {
                $setting = LandingPageSetting::first();
                $laundryLat = $setting ? (float) $setting->laundry_latitude : -0.1185067;
                $laundryLon = $setting ? (float) $setting->laundry_longitude : 100.566124;
                
                $earthRadius = 6371; // km
                $latFrom = deg2rad($laundryLat);
                $lonFrom = deg2rad($laundryLon);
                $latTo = deg2rad((float) $latitude);
                $lonTo = deg2rad((float) $longitude);

                $latDelta = $latTo - $latFrom;
                $lonDelta = $lonTo - $lonFrom;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                
                $effectiveDistanceKm = round($angle * $earthRadius, 1);
            }

            if ($effectiveDistanceKm > 2) {
                $pickupFee = ($effectiveDistanceKm - 2) * 5000;
            }
        }

        // --- 3. Diskon Promo ---
        $discount = 0;
        $resolvedPromoId = null;

        if (!empty($promoCode)) {
            $promo = Promo::where('code', $promoCode)
                ->where('is_active', true)
                ->where(fn($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>=', now()))
                ->first();

            if ($promo) {
                $resolvedPromoId = $promo->id;
                $discount = $promo->discount_type === 'percent'
                    ? $subtotal * ($promo->value / 100)
                    : $promo->value;

                $discount = min($discount, $subtotal); // Diskon tidak boleh melebihi subtotal
            } else {
                // Promo is invalid or expired
                throw new \Exception('Kode promo tidak valid atau sudah kadaluarsa.');
            }
        }

        // --- 4. Total Harga ---
        $totalPrice = $subtotal + $pickupFee - $discount;

        return [
            'subtotal'    => $subtotal,
            'pickup_fee'  => $pickupFee,
            'discount'    => $discount,
            'total_price' => max($totalPrice, 0),
            'distance_km' => $effectiveDistanceKm,
            'service_id'  => $resolvedServiceId,
            'bundle_id'   => $resolvedBundleId,
            'promo_id'    => $resolvedPromoId,
            'weight_kg'   => $effectiveWeight,
        ];
    }
}
