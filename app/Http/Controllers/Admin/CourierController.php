<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierController extends Controller
{
    /**
     * List semua kurir
     */
    public function index()
    {
        $couriers = Courier::withCount([
            'orders',
            'orders as active_orders_count' => function ($query) {
                $query->whereIn('status', ['pickup', 'process', 'finished', 'delivered'])
                      ->whereNotNull('courier_id');
            }
        ])
        ->with(['orders' => function ($query) {
            $query->where('status', '!=', 'completed')
                  ->select('id', 'courier_id', 'order_code', 'status', 'courier_task_type');
        }])
        ->orderBy('name')->paginate(15);

        return view('admin.couriers.index', compact('couriers'));
    }



    /**
     * Simpan kurir baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:255',
        ]);

        Courier::create(array_merge($validated, ['status' => 'idle', 'points' => 0]));

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Kurir ' . $validated['name'] . ' berhasil ditambahkan!');
    }



    /**
     * Update data kurir
     */
    public function update(Request $request, Courier $courier)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:255',
        ]);

        $courier->update($validated);

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Data kurir ' . $courier->name . ' berhasil diperbarui!');
    }

    /**
     * Hapus kurir (hanya jika idle)
     */
    public function destroy(Courier $courier)
    {
        if ($courier->status === 'on_duty') {
            return back()->with('error', 'Kurir ' . $courier->name . ' sedang bertugas dan tidak dapat dihapus!');
        }

        $name = $courier->name;
        $courier->delete();

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Kurir ' . $name . ' berhasil dihapus.');
    }

    /**
     * Assign kurir ke order
     */
    public function assignToOrder(Request $request, Order $order)
    {
        $request->validate([
            'courier_id'        => 'required|exists:couriers,id',
            'courier_task_type' => 'required|in:pickup,delivery,both',
        ]);

        $courier = Courier::findOrFail($request->courier_id);

        DB::beginTransaction();
        try {
            // Jika order sebelumnya sudah punya kurir lain, kembalikan status kurir lama ke idle
            if ($order->courier_id && $order->courier_id !== $courier->id) {
                $oldCourier = Courier::find($order->courier_id);
                // Cek apakah kurir lama masih punya order aktif lainnya
                $hasOtherActiveOrders = Order::where('courier_id', $oldCourier->id)
                    ->where('id', '!=', $order->id)
                    ->where('status', '!=', 'completed')
                    ->exists();
                if (!$hasOtherActiveOrders) {
                    $oldCourier->update(['status' => 'idle']);
                }
            }

            // Update order dengan kurir baru
            $order->update([
                'courier_id'        => $courier->id,
                'courier_task_type' => $request->courier_task_type,
            ]);

            // Set kurir ke on_duty
            $courier->update(['status' => 'on_duty']);

            // Catat di order tracking
            $taskLabel = match ($request->courier_task_type) {
                'pickup'   => 'Jemput',
                'delivery' => 'Antar',
                'both'     => 'Jemput & Antar',
            };

            OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => $order->status,
                'description' => "Kurir {$courier->name} ditugaskan untuk {$taskLabel} oleh Admin",
            ]);

            DB::commit();
            return back()->with('success', "Kurir {$courier->name} berhasil ditugaskan untuk order ini!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menugaskan kurir: ' . $e->getMessage());
        }
    }

    /**
     * Selesaikan tugas kurir — kurir kembali idle, poin +1
     */
    public function completeTask(Courier $courier)
    {
        DB::beginTransaction();
        try {
            // Tambah poin kurir
            $courier->increment('points');

            // Cek apakah kurir masih ada order aktif lainnya
            $hasActiveOrders = Order::where('courier_id', $courier->id)
                ->where('status', '!=', 'completed')
                ->exists();

            if (!$hasActiveOrders) {
                $courier->update(['status' => 'idle']);
            }

            DB::commit();
            return back()->with('success', "Tugas kurir {$courier->name} selesai! Poin bertambah menjadi {$courier->fresh()->points}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan tugas: ' . $e->getMessage());
        }
    }
}
