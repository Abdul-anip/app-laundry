<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;

echo "=== Testing PURE Filament Notification ===\n\n";

// Get admin
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    echo "ERROR: No admin found!\n";
    exit(1);
}
echo "Admin: {$admin->name}\n\n";

// Get order
$order = Order::latest()->first();
echo "Order: {$order->order_code}\n\n";

// Send Filament notification (the current way)
echo "Sending Filament notification...\n";
try {
    \Filament\Notifications\Notification::make()
        ->title('PURE TEST: Pesanan Baru! 🎉')
        ->body("Order {$order->order_code} dari {$order->customer_name}")
        ->icon('heroicon-o-shopping-bag')
        ->success()
        ->sendToDatabase($admin);
    
    echo "✅ Notification sent!\n\n";
} catch (\Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n\n";
}

// Check latest notification
sleep(1);
$latest = DB::table('notifications')
    ->where('notifiable_id', $admin->id)
    ->latest()
    ->first();

echo "Latest notification:\n";
echo "  Type: {$latest->type}\n";
echo "  Data keys: " . implode(', ', array_keys(json_decode($latest->data, true))) . "\n";
echo "\nFull data:\n";
echo json_encode(json_decode($latest->data), JSON_PRETTY_PRINT);
echo "\n\n";

// Check if Filament can recognize this
echo "Checking if this is Filament format...\n";
$data = json_decode($latest->data, true);
$hasFilamentKeys = isset($data['format']) || isset($data['body']) || isset($data['title']);
echo $hasFilamentKeys ? "✅ Has Filament keys\n" : "❌ Missing Filament keys\n";

echo "\n=== Try logging in to /admin now ===\n";
echo "Username: {$admin->email}\n";
echo "Check bell icon (wait 30 seconds for polling)\n";
