<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Latest Notification Data ===\n\n";

$notification = DB::table('notifications')
    ->orderBy('created_at', 'desc')
    ->first();

if ($notification) {
    echo "Type: {$notification->type}\n";
    echo "Created: {$notification->created_at}\n";
    echo "Read at: " . ($notification->read_at ?? 'Unread') . "\n";
    echo "\nData:\n";
    echo json_encode(json_decode($notification->data), JSON_PRETTY_PRINT);
    echo "\n";
} else {
    echo "No notifications found.\n";
}
