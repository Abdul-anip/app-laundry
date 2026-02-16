<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class FilamentNotificationHelper
{
    /**
     * Send a Filament notification to admin users
     * 
     * @param string $title
     * @param string $body
     * @param string $icon
     * @param string $iconColor (info, success, warning, danger)
     * @param string|null $actionUrl
     * @param string $actionLabel
     * @return void
     */
    public static function notifyAdmins(
        string $title,
        string $body,
        string $icon = 'heroicon-o-bell',
        string $iconColor = 'info',
        ?string $actionUrl = null,
        string $actionLabel = 'View'
    ): void
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            self::sendToUser($admin, $title, $body, $icon, $iconColor, $actionUrl, $actionLabel);
        }
    }
    
    /**
     * Send a Filament notification to a specific user
     * 
     * @param User $user
     * @param string $title
     * @param string $body
     * @param string $icon
     * @param string $iconColor
     * @param string|null $actionUrl
     * @param string $actionLabel
     * @return void
     */
    public static function sendToUser(
        User $user,
        string $title,
        string $body,
        string $icon = 'heroicon-o-bell',
        string $iconColor = 'info',
        ?string $actionUrl = null,
        string $actionLabel = 'View'
    ): void
    {
        $actions = [];
        
        if ($actionUrl) {
            $actions[] = [
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
                'label' => $actionLabel,
                'shouldClose' => false,
                'shouldMarkAsRead' => true,
                'shouldMarkAsUnread' => false,
                'shouldOpenUrlInNewTab' => false,
                'size' => 'sm',
                'tooltip' => null,
                'url' => $actionUrl,
                'view' => 'filament-notifications::actions.button-action',
            ];
        }
        
        $notificationData = [
            'actions' => $actions,
            'body' => $body,
            'color' => null,
            'duration' => 'persistent',
            'icon' => $icon,
            'iconColor' => $iconColor,
            'status' => $iconColor,
            'title' => $title,
            'view' => 'filament-notifications::notification',
            'viewData' => [],
            'format' => 'filament',
        ];
        
        DB::table('notifications')->insert([
            'id' => Str::uuid()->toString(),
            'type' => 'Filament\\Notifications\\DatabaseNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $user->id,
            'data' => json_encode($notificationData),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
