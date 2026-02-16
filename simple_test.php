<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;

echo "=== SIMPLE TEST: Exact Filament Documentation Method ===\n\n";

$admin = User::where('role', 'admin')->first();
$order = Order::latest()->first();

// Clear
DB::table('notifications')->truncate();

// Exactly as Filament docs show
echo "Using exact method from Filament docs...\n";
\Filament\Notifications\Notification::make()
    ->title('Order Baru: ' . $order->order_code)
    ->body("Customer: {$order->customer_name}")
    ->icon('heroicon-o-shopping-bag')
    ->success()
    ->sendToDatabase($admin); // SINGLE USER, not array

echo "Sent!\n\n";

sleep(1);

// Check
$count = DB::table('notifications')->count();
echo "Count in DB: {$count}\n\n";

if ($count > 0) {
    $n = DB::table('notifications')->first();
    echo "Notification found!\n";
    echo "  Type: {$n->type}\n";
    echo "  Notifiable Type: {$n->notifiable_type}\n";
    echo "  Notifiable ID: {$n->notifiable_id}\n\n";
    
    $data = json_decode($n->data, true);
    echo "Data:\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    
    if ($n->type === 'Filament\\Notifications\\DatabaseNotification') {
        echo "✅✅✅ SUCCESS! This is the correct type! ✅✅✅\n\n";
        echo "Now login to /admin and check bell icon!\n";
    }
}
