<?php

use App\Models\User;
use App\Models\Order;
use App\Notifications\NewOrderCreated;
use Illuminate\Support\Facades\Notification;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Debugging Admin Notifications ---\n";

// 1. Check Admin Users
$admins = User::where('role', 'admin')->get();
echo "Found " . $admins->count() . " admin users.\n";

if ($admins->count() > 0) {
    foreach ($admins as $admin) {
        echo "Admin: {$admin->name} ({$admin->email})\n";
    }
} else {
    echo "❌ No admin users found! Notification cannot be sent.\n";
    exit;
}

// 2. Check Order (Dummy or First)
$order = Order::first();
if (!$order) {
    echo "❌ No orders found in database. Creating a dummy order object for testing.\n";
    $order = new Order();
    $order->id = 999;
    $order->order_code = 'TEST-ORDER-001';
    $order->customer_name = 'Test Customer';
} else {
    echo "Using existing order: {$order->order_code}\n";
}

// 3. Try Sending Notification
echo "Attempting to send notification...\n";
try {
    Notification::send($admins, new NewOrderCreated($order));
    echo "✅ Notification::send() executed without error.\n";
} catch (\Exception $e) {
    echo "❌ Error sending notification: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit;
}

// 4. Verify Database
$count = \Illuminate\Support\Facades\DB::table('notifications')->count();
echo "Total notifications in DB: $count\n";

$latest = \Illuminate\Support\Facades\DB::table('notifications')->latest()->first();
if ($latest) {
    echo "Latest Notification Data:\n";
    print_r(json_decode($latest->data, true));
} else {
    echo "❌ No notifications found in table.\n";
}
