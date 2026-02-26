<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Service;
use App\Services\OrderCodeGenerator; // <-- TAMBAH INI
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
        if (empty(auth()->user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi nomor telepon Anda terlebih dahulu sebelum membuat pesanan.');
        }

        $services = Service::all();
        $bundles  = Bundle::all();

        return view('customer.orders.create', compact('services', 'bundles'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        if (empty(auth()->user()->phone)) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi nomor telepon Anda terlebih dahulu sebelum membuat pesanan.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'order_type'    => 'required|in:service,bundle',
            'service_id'    => 'required_if:order_type,service|nullable|exists:services,id',
            'bundle_id'     => 'required_if:order_type,bundle|nullable|exists:bundles,id',
            'weight_kg'     => 'required_if:order_type,service|nullable|numeric|min:1',
            'fabric_type'   => 'nullable|string|max:100',
            'distance_km'   => 'required|numeric|min:0',
            'promo_code'    => 'nullable|string|exists:promos,code',
        ]);

        try {
            DB::beginTransaction();

            // --- 1. Hitung Subtotal ---
            $subtotal        = 0;
            $estimatedWeight = 0;
            $serviceId       = null;
            $bundleId        = null;

            if ($request->order_type === 'service') {
                $service   = Service::findOrFail($request->service_id);
                $subtotal  = 0; // Dihitung ulang setelah admin input berat aktual
                $serviceId = $service->id;
            } else {
                $bundle   = Bundle::findOrFail($request->bundle_id);
                $subtotal = $bundle->price;
                $bundleId = $bundle->id;
            }

            // --- 2. Pickup Fee ---
            $pickupFee = 0;
            if ($request->distance_km > 2) {
                $pickupFee = ($request->distance_km - 2) * 5000;
            }

            // --- 3. Diskon Promo ---
            $discount = 0;
            $promoId  = null;

            if ($request->promo_code) {
                $checkPromo = Promo::where('code', $request->promo_code)->first();
                if ($checkPromo && (!$checkPromo->is_active || ($checkPromo->expired_at && $checkPromo->expired_at->isPast()))) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['promo_code' => 'Kode promo sudah habis atau tidak aktif.']);
                }

                $promo = Promo::where('code', $request->promo_code)
                    ->where('is_active', true)
                    ->where(fn($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>=', now()))
                    ->first();

                if ($promo) {
                    $promoId  = $promo->id;
                    $discount = $promo->discount_type === 'percent'
                        ? $subtotal * ($promo->value / 100)
                        : $promo->value;

                    $discount = min($discount, $subtotal);
                }
            }

            // --- 4. Total Harga ---
            $totalPrice = $subtotal + $pickupFee - $discount;

            // --- 5. Generate Order Code (ATOMIC — aman dari race condition) ---
            // OrderCodeGenerator::generate() menggunakan SELECT FOR UPDATE
            // sehingga tidak mungkin dua request mendapat kode yang sama,
            // bahkan jika dikirim pada milidetik yang sama.
            $orderCode = OrderCodeGenerator::generate();

            // --- 6. Buat Order ---
            $order = Order::create([
                'order_code'       => $orderCode,
                'user_id'          => auth()->id(),
                'customer_user_id' => auth()->id(),
                'order_source'     => 'online',
                'service_id'       => $serviceId,
                'bundle_id'        => $bundleId,
                'promo_id'         => $promoId,
                'customer_name'    => $request->customer_name,
                'phone'            => $request->phone,
                'address'          => $request->address,
                'latitude'         => $request->latitude,
                'longitude'        => $request->longitude,
                'fabric_type'      => $request->fabric_type,
                'weight_kg'        => $estimatedWeight,
                'pickup_date'      => null,
                'pickup_time'      => null,
                'distance_km'      => $request->distance_km,
                'pickup_fee'       => $pickupFee,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'total_price'      => $totalPrice,
                'status'           => 'pending',
            ]);

            \App\Models\OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => 'pending',
                'description' => 'Order created by customer',
            ]);

            // Notifikasi admin
            try {
                \App\Helpers\FilamentNotificationHelper::notifyAdmins(
                    title: 'Pesanan Baru Masuk! 🆕',
                    body: "Pesanan {$order->order_code} dari {$order->customer_name} perlu diproses.",
                    icon: 'heroicon-o-shopping-bag',
                    iconColor: 'info',
                    actionUrl: route('admin.orders.show', $order),
                    actionLabel: 'View Order'
                );
            } catch (\Exception $notifEx) {
                Log::warning('Admin notification failed: ' . $notifEx->getMessage());
            }

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order berhasil dibuat!');

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
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        auth()->user()->unreadNotifications->where('data.order_id', $order->id)->markAsRead();

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Download order proof as PDF.
     */
    public function downloadProof(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.orders.pdf', compact('order'));
        return $pdf->download('invoice-' . $order->order_code . '.pdf');
    }

    /**
     * Customer confirms order receipt.
     */
    public function confirm(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Order must be delivered before confirming.');
        }

        try {
            DB::beginTransaction();

            $order->update(['status' => 'completed']);

            \App\Models\OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => 'completed',
                'description' => 'Order received and confirmed by customer',
            ]);

            try {
                \App\Helpers\FilamentNotificationHelper::notifyAdmins(
                    title: 'Order Completed ✅',
                    body: "Customer {$order->customer_name} telah mengkonfirmasi penerimaan order {$order->order_code}.",
                    icon: 'heroicon-o-check-circle',
                    iconColor: 'success',
                    actionUrl: route('admin.orders.show', $order),
                    actionLabel: 'View Order'
                );
            } catch (\Exception $notifEx) {
                Log::warning('Admin notification failed: ' . $notifEx->getMessage());
            }

            DB::commit();

            return back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi selesai.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order confirmation failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengkonfirmasi pesanan. Error: ' . $e->getMessage());
        }
    }
}