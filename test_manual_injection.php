<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;

echo "=== MANUAL INJECTION TEST ===\n\n";

$admin = User::where('role', 'admin')->first();
$order = Order::latest()->first();

// Clear old notifications
DB::table('notifications')->truncate();
echo "Cleared old notifications\n\n";

// Manual injection with CORRECT Filament type
echo "Inserting notification manually...\n";

$notificationData = [
    'actions' => [
        [
            'name' => 'view',
            'color' => null,
            'event' => null,
            'eventData' => [],
            'dispatchDirection' => false,
            'dispatchToComponent' => null,
            'extraAttributes' => [],
            'icon' => null,
            'iconPosition' => 'before',
            'iconSize' => null,
            'isOutlined' => false,
            'isDisabled' => false,
            'label' => 'View',
            'shouldClose' => false,
            'shouldMarkAsRead' => true,
            'shouldMarkAsUnread' => false,
            'shouldOpenUrlInNewTab' => true,
            'size' => 'sm',
            'tooltip' => null,
            'url' => route('filament.admin.resources.orders.view', $order),
            'view' => 'filament-notifications::actions.button-action',
        ],
    ],
    'body' => "Pesanan {$order->order_code} dari {$order->customer_name} perlu diproses.",
    'color' => null,
    'duration' => 'persistent',
    'icon' => 'heroicon-o-shopping-bag',
    'iconColor' => 'info',
    'status' => 'info',
    'title' => 'Pesanan Baru Masuk! 🆕',
    'view' => 'filament-notifications::notification',
    'viewData' => [],
    'format' => 'filament',
];

$inserted = DB::table('notifications')->insert([
    'id' => Str::uuid()->toString(),
    'type' => 'Filament\\Notifications\\DatabaseNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => $admin->id,
    'data' => json_encode($notificationData),
    'read_at' => null,
    'created_at' => now(),
    'updated_at' => now(),
]);

echo $inserted ? "✅ Inserted successfully!\n\n" : "❌ Failed to insert\n\n";

// Verify
$count = DB::table('notifications')->count();
echo "Notifications in DB: {$count}\n";

$notification = DB::table('notifications')->first();
if ($notification) {
    echo "\n✅✅✅ SUCCESS! ✅✅✅\n";
    echo "Type: {$notification->type}\n";
    echo "Notifiable ID: {$notification->notifiable_id}\n";
    echo "\nData structure:\n";
    echo json_encode(json_decode($notification->data), JSON_PRETTY_PRINT);
    echo "\n\n";
    echo "NOW LOGIN TO /admin AS {$admin->email}\n";
    echo "Check the bell icon - notification SHOULD appear now!\n";
}
