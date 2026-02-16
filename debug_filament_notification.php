<?php

/**
 * Debug script untuk test Filament notifications
 * Run dengan: php debug_filament_notification.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;

echo "=== Debug Filament Notification ===\n\n";

// 1. Check if admin users exist
echo "1. Checking admin users...\n";
$admins = User::where('role', 'admin')->get();
echo "   Found {$admins->count()} admin(s)\n";
foreach ($admins as $admin) {
    echo "   - {$admin->name} ({$admin->email})\n";
}
echo "\n";

if ($admins->count() === 0) {
    echo "ERROR: No admin users found! Please create an admin user first.\n";
    exit(1);
}

// 2. Check notifications table
echo "2. Checking notifications table...\n";
try {
    $notificationCount = DB::table('notifications')->count();
    echo "   Total notifications in DB: {$notificationCount}\n";
    
    $unreadCount = DB::table('notifications')->whereNull('read_at')->count();
    echo "   Unread notifications: {$unreadCount}\n";
} catch (\Exception $e) {
    echo "   ERROR: {$e->getMessage()}\n";
}
echo "\n";

// 3. Get a sample order
echo "3. Getting sample order...\n";
$order = Order::latest()->first();
if (!$order) {
    echo "   ERROR: No orders found in database!\n";
    exit(1);
}
echo "   Using Order: {$order->order_code}\n";
echo "   Customer: {$order->customer_name}\n";
echo "\n";

// 4. Send test notification
echo "4. Sending test notification...\n";
try {
    $admin = $admins->first();
    
    \Filament\Notifications\Notification::make()
        ->title('TEST: Pesanan Baru Masuk! 🆕')
        ->body("TEST: Pesanan {$order->order_code} dari {$order->customer_name}")
        ->icon('heroicon-o-shopping-bag')
        ->info()
        ->actions([
            \Filament\Notifications\Actions\Action::make('view')
                ->button()
                ->url(route('filament.admin.resources.orders.view', $order), shouldOpenInNewTab: true)
                ->markAsRead(),
        ])
        ->sendToDatabase($admin);
    
    echo "   ✅ Test notification sent to {$admin->name}!\n";
} catch (\Exception $e) {
    echo "   ❌ ERROR: {$e->getMessage()}\n";
    echo "   Stack trace:\n";
    echo $e->getTraceAsString();
}
echo "\n";

// 5. Verify notification was saved
echo "5. Verifying notification in database...\n";
$latestNotification = DB::table('notifications')
    ->where('notifiable_id', $admin->id)
    ->latest()
    ->first();

if ($latestNotification) {
    echo "   ✅ Found notification in database!\n";
    echo "   ID: {$latestNotification->id}\n";
    echo "   Type: {$latestNotification->type}\n";
    echo "   Created: {$latestNotification->created_at}\n";
    echo "   Data: " . substr($latestNotification->data, 0, 100) . "...\n";
} else {
    echo "   ❌ No notification found in database!\n";
}

echo "\n=== Done! ===\n";
echo "Now login to /admin and check the bell icon.\n";
echo "Make sure to wait 30 seconds for polling to refresh.\n";
