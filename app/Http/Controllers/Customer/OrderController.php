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
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        // Check if user has phone number
        if (empty(auth()->user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi nomor telepon Anda terlebih dahulu sebelum membuat pesanan.');
        }
        
        $services = Service::all();
        $bundles = Bundle::all();
        
        return view('customer.orders.create', compact('services', 'bundles'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // Double check user has phone number
        if (empty(auth()->user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi nomor telepon Anda terlebih dahulu sebelum membuat pesanan.');
        }
        
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'order_type' => 'required|in:service,bundle',
            'service_id' => 'required_if:order_type,service|nullable|exists:services,id',
            'bundle_id' => 'required_if:order_type,bundle|nullable|exists:bundles,id',
            'weight_kg' => 'required_if:order_type,service|nullable|numeric|min:1',
            'fabric_type' => 'nullable|string|max:100',
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
                // Determine if promo exists but is invalid (inactive or expired) for error reporting
                $checkPromo = Promo::where('code', $request->promo_code)->first();
                if ($checkPromo) {
                    if (!$checkPromo->is_active || ($checkPromo->expired_at && $checkPromo->expired_at->isPast())) {
                        return back()->withInput()->withErrors(['promo_code' => 'code promo sudah habis']);
                    }
                }

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
                'customer_user_id' => auth()->id(), // Same as user_id for online orders
                'order_source' => 'online',
                'service_id' => $serviceId,
                'bundle_id' => $bundleId,
                'promo_id' => $promoId,
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'fabric_type' => $request->fabric_type,
                'weight_kg' => $request->weight_kg ?? 0,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'distance_km' => $request->distance_km,
                'pickup_fee' => $pickupFee,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withInput()->withErrors(['error' => 'Gagal membuat pesanan. Silakan coba lagi atau hubungi admin.']);
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
