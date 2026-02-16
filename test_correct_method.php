<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

echo "=== Testing CORRECT Filament Notification Method ===\n\n";

$admin = User::where('role', 'admin')->first();
$order = Order::latest()->first();

echo "Sending notification with CORRECT method...\n\n";

// Clear old notifications first
DB::table('notifications')->where('notifiable_id', $admin->id)->delete();
echo "Cleared old notifications\n\n";

// Method 1: Try send()
echo "Method 1: Using send()...\n";
try {
    Notification::make()
        ->title('Test Method 1: send()')
        ->body("Order {$order->order_code}")
        ->icon('heroicon-o-shopping-bag')
        ->info()
        ->send();
    echo "  ✅ Sent with send()\n";
} catch (\Exception $e) {
    echo "  ❌ Error: {$e->getMessage()}\n";
}

sleep(1);
$count1 = DB::table('notifications')->where('notifiable_id', $admin->id)->count();
echo "  Notifications in DB: {$count1}\n\n";

// Method 2: Try sendToDatabase with recipient
echo "Method 2: Using sendToDatabase with recipients array...\n";
try {
    Notification::make()
        ->title('Test Method 2: sendToDatabase with array')
        ->body("Order {$order->order_code}")
        ->icon('heroicon-o-shopping-bag')
        ->success()
        ->sendToDatabase([$admin]);
    echo "  ✅ Sent with sendToDatabase(array)\n";
} catch (\Exception $e) {
    echo "  ❌ Error: {$e->getMessage()}\n";
}

sleep(1);
$count2 = DB::table('notifications')->where('notifiable_id', $admin->id)->count();
echo "  Notifications in DB: {$count2}\n\n";

// Check what type was created
echo "Checking notification types:\n";
$latest = DB::table('notifications')
    ->where('notifiable_id', $admin->id)
    ->latest()
    ->first();

if ($latest) {
    echo "  Latest notification type: {$latest->type}\n";
    echo "  Expected type: Filament\\Notifications\\DatabaseNotification\n";
    echo "  " . ($latest->type === 'Filament\\Notifications\\DatabaseNotification' ? '✅ CORRECT!' : '❌ WRONG!') . "\n";
} else {
    echo "  No notifications found!\n";
}
