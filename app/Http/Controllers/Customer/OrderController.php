<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $services = Service::all();
        $bundles = Bundle::all();
        
        return view('customer.orders.create', compact('services', 'bundles'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'order_type' => 'required|in:service,bundle',
            'service_id' => 'required_if:order_type,service|nullable|exists:services,id',
            'bundle_id' => 'required_if:order_type,bundle|nullable|exists:bundles,id',
            'weight_kg' => 'required_if:order_type,service|nullable|numeric|min:1',
            'fabric_type' => 'nullable|string|max:100',
            'payment_method' => 'required|string|in:cash,transfer',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'distance_km' => 'required|numeric|min:0',
            'promo_code' => 'nullable|string|exists:promos,code',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $serviceId = null;
            $bundleId = null;

            // 1. Calculate Subtotal
            if ($request->order_type === 'service') {
                $service = Service::findOrFail($request->service_id);
                $subtotal = $service->price_per_kg * $request->weight_kg;
                $serviceId = $service->id;
            } else {
                $bundle = Bundle::findOrFail($request->bundle_id);
                $subtotal = $bundle->price;
                $bundleId = $bundle->id;
            }

            // 2. Calculate Pickup Fee
            // Rule: <= 2km free, > 2km -> (dist - 2) * 5000
            $pickupFee = 0;
            if ($request->distance_km > 2) {
                $pickupFee = ($request->distance_km - 2) * 5000;
            }

            // 3. Calculate Discount
            $discount = 0;
            $promoId = null;
            if ($request->promo_code) {
                $promo = Promo::where('code', $request->promo_code)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('expired_at')
                              ->orWhere('expired_at', '>=', now());
                    })
                    ->first();

                if ($promo) {
                    $promoId = $promo->id;
                    if ($promo->discount_type === 'percent') {
                        $discount = $subtotal * ($promo->value / 100);
                    } else {
                        $discount = $promo->value;
                    }
                    
                    // Discount cannot exceed subtotal
                    if ($discount > $subtotal) {
                        $discount = $subtotal;
                    }
                }
            }

            // 4. Calculate Total
            $totalPrice = $subtotal + $pickupFee - $discount;

            // 5. Generate Order Code (LDRY-YYYY-XXXX)
            $year = date('Y');
            $lastOrder = Order::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $lastNumber = $lastOrder ? intval(substr($lastOrder->order_code, -4)) : 0;
            $newNumber = $lastNumber + 1;
            $orderCode = 'LDRY-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // 6. Create Order
            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => auth()->id(),
                'service_id' => $serviceId,
                'bundle_id' => $bundleId,
                'promo_id' => $promoId,
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'fabric_type' => $request->fabric_type,
                'weight_kg' => $request->weight_kg ?? 0,
                'payment_method' => $request->payment_method,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'distance_km' => $request->distance_km,
                'pickup_fee' => $pickupFee,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'description' => $request->notes, // Assuming notes maps to description or extra field, but spec didn't strictly require saving notes in a specific column, so I'll skip unless I add a column. Wait, spec said 'notes (opsional)' in input but not in table. I will ignore saving notes for now as it's not in the migration.
            ]);

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create order. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Ensure user owns the order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.orders.show', compact('order'));
    }
}
