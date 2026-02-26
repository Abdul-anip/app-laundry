<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\Promo;
use App\Models\Service;
use App\Services\OrderCodeGenerator; // <-- TAMBAH INI
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfflineOrderController extends Controller
{
    public function create()
    {
        $services = Service::all();
        $bundles  = Bundle::all();

        return view('admin.orders.create_offline', compact('services', 'bundles'));
    }

    /**
     * Display POS mode (fullscreen cashier interface)
     */
    public function pos()
    {
        $services = Service::all();
        $bundles  = Bundle::all();

        return view('admin.pos.index', compact('services', 'bundles'));
    }

    /**
     * Get customers for Select2 search
     */
    public function getCustomers(Request $request)
    {
        $search    = $request->get('q', '');
        $customers = [];

        // 1. Registered users
        $users = \App\Models\User::where('role', 'customer')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

        foreach ($users as $user) {
            $customers[] = [
                'id'      => 'user_' . $user->id,
                'text'    => $user->name . ' (' . $user->email . ')',
                'type'    => 'user',
                'user_id' => $user->id,
                'name'    => $user->name,
                'phone'   => $user->phone ?? '',
            ];
        }

        // 2. Offline customers dari riwayat order
        $offlineCustomers = Order::where('order_source', 'offline')
            ->whereNull('customer_user_id')
            ->where('customer_name', 'LIKE', "%{$search}%")
            ->select('customer_name', 'phone')
            ->distinct()
            ->limit(10)
            ->get();

        foreach ($offlineCustomers as $customer) {
            $customers[] = [
                'id'    => 'offline_' . $customer->customer_name,
                'text'  => $customer->customer_name . ' (Offline - ' . $customer->phone . ')',
                'type'  => 'offline',
                'name'  => $customer->customer_name,
                'phone' => $customer->phone,
            ];
        }

        return response()->json(['results' => $customers]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_type'  => 'nullable|in:user,offline,manual',
            'customer_id'    => 'nullable|string',
            'customer_name'  => 'required_if:customer_type,manual|nullable|string|max:255',
            'phone'          => 'required|string|max:20',
            'order_type'     => 'required|in:service,bundle',
            'service_id'     => 'required_if:order_type,service|nullable|exists:services,id',
            'bundle_id'      => 'required_if:order_type,bundle|nullable|exists:bundles,id',
            'weight_kg'      => 'required_if:order_type,service|nullable|numeric|min:1',
            'fabric_type'    => 'nullable|string|max:100',
            'payment_method' => 'required|string|in:cash,transfer',
            'promo_code'     => 'nullable|string|exists:promos,code',
        ]);

        try {
            DB::beginTransaction();

            // --- Tentukan Data Customer ---
            $customerUserId = null;
            $customerName   = '';
            $customerPhone  = $request->phone;

            if ($request->customer_type === 'user' && $request->customer_id) {
                $userId = str_replace('user_', '', $request->customer_id);
                $user   = \App\Models\User::find($userId);
                if ($user) {
                    $customerUserId = $user->id;
                    $customerName   = $user->name;
                    $customerPhone  = $user->phone ?? $request->phone;
                }
            } elseif ($request->customer_type === 'offline' && $request->customer_id) {
                $customerName = str_replace('offline_', '', $request->customer_id);
            } else {
                $customerName = $request->customer_name;
            }

            // --- Hitung Menggunakan Pricing Service ---
            $pricingService = new \App\Services\PricingService();
            
            try {
                $pricing = $pricingService->calculate(
                    orderType: $request->order_type,
                    serviceId: $request->service_id,
                    bundleId: $request->bundle_id,
                    weightKg: (float) $request->weight_kg,
                    distanceKm: 0,
                    promoCode: $request->promo_code,
                    latitude: null,
                    longitude: null,
                    isOffline: true
                );
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()->withErrors(['promo_code' => $e->getMessage()]);
            }

            // --- 5. Generate Order Code (ATOMIC — aman dari race condition) ---
            // Sama persis dengan online order: menggunakan SELECT FOR UPDATE
            // sehingga POS kasir yang dibuka di banyak tab/komputer sekalipun
            // tidak akan menghasilkan kode duplikat.
            $orderCode = OrderCodeGenerator::generate();

            // --- 6. Buat Order ---
            $order = Order::create([
                'order_code'       => $orderCode,
                'user_id'          => auth()->id(), // Admin ID
                'customer_user_id' => $customerUserId,
                'order_source'     => 'offline',
                'service_id'       => $pricing['service_id'],
                'bundle_id'        => $pricing['bundle_id'],
                'promo_id'         => $pricing['promo_id'],
                'customer_name'    => $customerName,
                'phone'            => $customerPhone,
                'address'          => $request->address ?? 'Walk-in Customer (Offline)',
                'fabric_type'      => $request->fabric_type,
                'weight_kg'        => $pricing['weight_kg'],
                'payment_method'   => $request->payment_method,
                'pickup_date'      => now()->toDateString(),
                'pickup_time'      => now()->toTimeString(),
                'distance_km'      => $pricing['distance_km'],
                'pickup_fee'       => $pricing['pickup_fee'],
                'subtotal'         => $pricing['subtotal'],
                'discount'         => $pricing['discount'],
                'total_price'      => $pricing['total_price'],
                'status'           => 'process',
                'description'      => $request->notes,
            ]);

            OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => 'process',
                'description' => 'Offline Order created by Admin',
            ]);

            DB::commit();

            return redirect()->route('admin.pos')
                ->with('success', 'Offline Order berhasil dibuat!')
                ->with('print_order_id', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Offline Order failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal membuat order: ' . $e->getMessage()]);
        }
    }

    /**
     * AJAX: Validate promo code
     */
    public function checkPromo(Request $request)
    {
        $code     = strtoupper($request->code);
        $subtotal = (float) $request->subtotal;

        $promo = Promo::where('code', $code)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('expired_at')->orWhereDate('expired_at', '>=', now()))
            ->first();

        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Kode promo tidak valid atau sudah kadaluarsa.']);
        }

        $discount = $promo->discount_type === 'percent'
            ? $subtotal * ($promo->value / 100)
            : $promo->value;

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return response()->json([
            'valid'         => true,
            'promo_id'      => $promo->id,
            'discount_type' => $promo->discount_type,
            'value'         => $promo->value,
            'discount'      => round($discount),
            'message'       => 'Promo berhasil diterapkan!',
        ]);
    }
}