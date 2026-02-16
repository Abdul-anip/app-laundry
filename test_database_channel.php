<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;
use Filament\Notifications\Notification;

echo "=== Testing database() Channel ===\n\n";

$admin = User::where('role', 'admin')->first();
$order = Order::latest()->first();

// Clear old
DB::table('notifications')->truncate();
echo "Cleared all notifications\n\n";

// Test with database() channel
echo "Attempting with ->database() chain...\n";
try {
    $notification = Notification::make()
        ->title('FINAL TEST: New Order! 🎉')
        ->body("Order {$order->order_code} from {$order->customer_name}")
        ->icon('heroicon-o-shopping-bag')
        ->success()
        ->actions([
            \Filament\Notifications\Actions\Action::make('view')
                ->button()
                ->url(route('filament.admin.resources.orders.view', $order), shouldOpenInNewTab: true),
        ]);
    
    // Try calling send on the model
    $admin->notify($notification->toNotification());
    
    echo "  ✅ Sent!\n";
} catch (\Exception $e) {
    echo "  ❌ Error: {$e->getMessage()}\n";
    echo "  Trace: " . $e->getTraceAsString() . "\n";
}

sleep(1);

// Check database
$dbCount = DB::table('notifications')->count();
echo "\nNotifications in database: {$dbCount}\n";

if ($dbCount > 0) {
    $latest = DB::table('notifications')->latest()->first();
    echo "\nLatest notification:\n";
    echo "  Type: {$latest->type}\n";
    echo "  Notifiable ID: {$latest->notifiable_id}\n";
    echo "  Data preview: " . substr($latest->data, 0, 100) . "...\n";
    
    $data = json_decode($latest->data, true);
    echo "\nData structure:\n";
    echo json_encode($data, JSON_PRETTY_PRINT);
    
    if ($latest->type === 'Filament\\Notifications\\DatabaseNotification') {
        echo "\n\n✅✅✅ SUCCESS! Type is correct for Filament! ✅✅✅\n";
    } else {
        echo "\n\n❌ Type is still wrong: {$latest->type}\n";
    }
}

echo "\n\n=== Next Step ===\n";
echo "If success, login to /admin as {$admin->email}\n";
echo "Check bell icon - notification should appear!\n";
