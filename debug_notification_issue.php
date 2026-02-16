<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Debugging Filament Notification Issue ===\n\n";

// Get admin
$admin = User::where('role', 'admin')->first();
echo "1. Admin user check:\n";
echo "   Name: {$admin->name}\n";
echo "   Email: {$admin->email}\n";
echo "   ID: {$admin->id}\n";
echo "   Has Notifiable trait: " . (method_exists($admin, 'notifications') ? 'YES' : 'NO') . "\n\n";

// Check unread notifications using Laravel
echo "2. Laravel unreadNotifications count:\n";
$laravelUnread = $admin->unreadNotifications()->count();
echo "   Count: {$laravelUnread}\n\n";

// Check database directly
echo "3. Direct database query:\n";
$dbNotifications = DB::table('notifications')
    ->where('notifiable_type', 'App\\Models\\User')
    ->where('notifiable_id', $admin->id)
    ->whereNull('read_at')
    ->get();
    
echo "   Total unread in DB: {$dbNotifications->count()}\n";

if ($dbNotifications->count() > 0) {
    echo "\n   Sample notification types:\n";
    foreach ($dbNotifications->take(3) as $n) {
        echo "   - {$n->type}\n";
    }
}

// Check specifically for Filament type
echo "\n4. Checking for Filament notification type:\n";
$filamentType = DB::table('notifications')
    ->where('notifiable_id', $admin->id)
    ->where('type', 'Filament\\Notifications\\DatabaseNotification')
    ->count();
echo "   Filament type count: {$filamentType}\n";

// Check what Filament actually queries
echo "\n5. What types exist in database:\n";
$types = DB::table('notifications')
    ->where('notifiable_id', $admin->id)
    ->select('type', DB::raw('count(*) as count'))
    ->groupBy('type')
    ->get();
    
foreach ($types as $type) {
    echo "   - {$type->type}: {$type->count}\n";
}

echo "\n=== DIAGNOSIS ===\n";
if ($filamentType == 0 && $dbNotifications->count() > 0) {
    echo "❌ PROBLEM FOUND: Notifications use Laravel class type, not Filament type!\n";
    echo "   Filament expects: Filament\\Notifications\\DatabaseNotification\n";
    echo "   But we have: App\\Notifications\\NewOrderCreated\n\n";
    echo "SOLUTION: Need to ensure Filament notifications are sent correctly.\n";
} else {
    echo "✅ Notification types look correct.\n";
}
