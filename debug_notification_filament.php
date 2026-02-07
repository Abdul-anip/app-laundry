<?php

use App\Models\User;
use Filament\Notifications\Notification;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Debugging Admin Notifications (Filament Way) ---\n";

// 1. Check Admin Users
$admins = User::where('role', 'admin')->get();
echo "Found " . $admins->count() . " admin users.\n";

if ($admins->count() > 0) {
    foreach ($admins as $admin) {
        echo "Sending database notification to: {$admin->name} ({$admin->email})\n";
        
        try {
            Notification::make()
                ->title('Test Notification from Debug Script')
                ->body('This is a test notification sent via Filament Notification facade.')
                ->icon('heroicon-o-check-circle') // Ensure this icon exists
                ->success()
                ->sendToDatabase($admin);
                
            echo "✅ Notification sent successfully.\n";
        } catch (\Exception $e) {
            echo "❌ Failed to send notification: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "❌ No admin users found.\n";
}
