<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['service', 'bundle', 'promo'])
            ->orderBy('created_at', 'desc');

        // Filter: status (bisa multiple)
        if ($request->filled('status')) {
            $query->whereIn('status', (array) $request->status);
        }

        // Filter: source
        if ($request->filled('source')) {
            $query->where('order_source', $request->source);
        }

        // Filter: date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_until')) {
            $query->whereDate('created_at', '<=', $request->date_until);
        }

        // Search: order_code / customer_name
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('order_code', 'like', "%$q%")
                   ->orWhere('customer_name', 'like', "%$q%")
                   ->orWhere('phone', 'like', "%$q%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'service', 'bundle', 'promo', 'orderTrackings', 'review']);

        // Mark relevant notifications as read
        if (auth()->check()) {
            auth()->user()->unreadNotifications
                ->where('data.order_id', $order->id)
                ->markAsRead();
        }

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Advance order to next status (replaces Filament advance_status action)
     */
    public function advanceStatus(Request $request, Order $order)
    {
        // Validation: can't proceed from 'pickup' if weight is 0 for service orders
        if ($order->status === 'pickup' && $order->service_id && $order->weight_kg <= 0) {
            return back()->with('error', 'Harap input berat aktual terlebih dahulu sebelum memproses order!');
        }

        $nextStatus = match ($order->status) {
            'pending'  => 'pickup',
            'pickup'   => 'process',
            'process'  => 'finished',
            'finished' => 'delivered',
            default    => null,
        };

        if (!$nextStatus) {
            return back()->with('error', 'Order sudah di status akhir.');
        }

        DB::beginTransaction();
        try {
            $order->update(['status' => $nextStatus]);

            OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => $nextStatus,
                'description' => 'Status updated to ' . ucfirst($nextStatus) . ' by Admin',
            ]);

            // Point System: tambah poin saat order selesai (finished)
            if ($nextStatus === 'finished') {
                $points = floor($order->total_price / 1000);
                if ($points > 0 && $order->user) {
                    $order->user->increment('points', $points);

                    OrderTracking::create([
                        'order_id'    => $order->id,
                        'status'      => 'point_added',
                        'description' => "Customer earned {$points} points from spending Rp " . number_format($order->total_price, 0, ',', '.'),
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Status order berhasil diupdate ke ' . ucfirst($nextStatus) . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Input actual weight and recalculate price (replaces Filament input_weight action)
     */
    public function inputWeight(Request $request, Order $order)
    {
        $request->validate([
            'weight_kg' => 'required|numeric|min:0.1',
        ]);

        $weight   = $request->weight_kg;
        $subtotal = 0;

        if ($order->service_id && $order->service) {
            $subtotal = $order->service->price_per_kg * $weight;
        } elseif ($order->bundle_id && $order->bundle) {
            $subtotal = $order->subtotal; // Bundle harga tetap
        }

        $discount = 0;
        if ($order->promo_id && $order->promo) {
            if ($order->promo->discount_type === 'percent') {
                $discount = $subtotal * ($order->promo->value / 100);
            } else {
                $discount = $order->promo->value;
            }
            if ($discount > $subtotal) $discount = $subtotal;
        }

        $totalPrice = $subtotal + $order->pickup_fee - $discount;

        $order->update([
            'weight_kg'   => $weight,
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'total_price' => $totalPrice,
        ]);

        return back()->with('success', 'Berat & harga berhasil diupdate!');
    }

    /**
     * WhatsApp: Pickup Notification
     */
    public function waPickup(Order $order)
    {
        $alreadySent = $order->orderTrackings()
            ->where('description', 'WhatsApp Pickup notification sent')
            ->exists();

        if (!$alreadySent) {
            OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => 'pickup_notified',
                'description' => 'WhatsApp Pickup notification sent',
            ]);
        }

        $text = "Halo {$order->customer_name}, kurir VIP Laundry sedang menuju lokasi Anda untuk penjemputan order {$order->order_code}. Mohon ditunggu ya! \n\n- Terima Kasih";
        $url  = 'https://wa.me/' . $this->formatPhone($order->phone) . '?text=' . urlencode($text);

        return redirect()->away($url);
    }

    /**
     * WhatsApp: Invoice/Tagihan
     */
    public function waInvoice(Order $order)
    {
        $text = "Halo {$order->customer_name}, Order {$order->order_code} sudah kami timbang.\n\nBerat: " . floatval($order->weight_kg) . " Kg\nTotal: Rp " . number_format($order->total_price, 0, ',', '.') . "\n\nDetail: " . route('customer.orders.show', $order) . "\n\nOrder segera kami proses. Terima kasih!";
        $url  = 'https://wa.me/' . $this->formatPhone($order->phone) . '?text=' . urlencode($text);

        return redirect()->away($url);
    }

    /**
     * WhatsApp: General Status Update
     */
    public function waStatus(Order $order)
    {
        $text = "Halo {$order->customer_name}, update status order {$order->order_code}: *" . ucfirst($order->status) . "*.\n\nCek detail: " . route('customer.orders.show', $order);
        $url  = 'https://wa.me/' . $this->formatPhone($order->phone) . '?text=' . urlencode($text);

        return redirect()->away($url);
    }

    /**
     * Generate and download PDF receipt
     */
    public function printReceipt(Order $order)
    {
        $order->load(['user', 'service', 'bundle', 'promo']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.receipt', compact('order'));
        return $pdf->download('struk-' . $order->order_code . '.pdf');
    }

    private function formatPhone(?string $phone): string
    {
        if (!$phone) return '';
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}
