<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderTracking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoConfirmOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:autoconfirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically confirm orders that have been delivered for more than 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for orders to auto-confirm...');

        $count = 0;

        Order::where('status', 'delivered')
            ->where('updated_at', '<', Carbon::now()->subHours(24))
            ->chunk(100, function ($orders) use (&$count) {
                foreach ($orders as $order) {
                    try {
                        DB::transaction(function () use ($order) {
                            $order->update(['status' => 'completed']);

                            OrderTracking::create([
                                'order_id'    => $order->id,
                                'status'      => 'completed',
                                'description' => 'Automatically confirmed by system (24h timeout)',
                            ]);

                            // Award Points safely
                            $points = floor($order->total_price / 1000);
                            if ($points > 0 && $order->user) {
                                $alreadyAwarded = OrderTracking::where('order_id', $order->id)
                                    ->where('status', 'point_added')
                                    ->exists();

                                if (!$alreadyAwarded) {
                                    $order->user->increment('points', $points);
                                    OrderTracking::create([
                                        'order_id'    => $order->id,
                                        'status'      => 'point_added',
                                        'description' => "Customer earned {$points} points from spending Rp " . number_format($order->total_price, 0, ',', '.'),
                                    ]);
                                }
                            }
                        });

                        $count++;
                        $this->info("Order {$order->order_code} confirmed.");

                    } catch (\Exception $e) {
                        Log::error("Failed to auto-confirm order {$order->id}: " . $e->getMessage());
                        $this->error("Failed to confirm {$order->order_code}");
                    }
                }
            });

        if ($count === 0) {
            $this->info('No orders found requiring auto-confirmation.');
        } else {
            $this->info("Successfully auto-confirmed {$count} orders.");
        }
    }
}
