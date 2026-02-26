<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** Tandai satu notifikasi sebagai sudah dibaca */
    public function markRead(string $id)
    {
        $notif = auth()->user()->notifications()->where('id', $id)->first();
        if ($notif) {
            $notif->markAsRead();
        }

        $redirectUrl = $notif?->data['url'] ?? route('admin.dashboard');
        return redirect($redirectUrl);
    }

    /** Tandai semua notifikasi sebagai sudah dibaca */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /** Fetch recent notifications for real-time polling */
    public function fetch()
    {
        $user = auth()->user();
        
        $unreadCount = $user->unreadNotifications()->count();
        $notifications = $user->notifications()->latest()->take(10)->get()->map(function($notif) {
            return [
                'id' => $notif->id,
                'title' => $notif->data['title'] ?? 'Notifikasi',
                'body' => $notif->data['body'] ?? '',
                'url' => route('admin.notifications.read', $notif->id),
                'read_at' => $notif->read_at,
                'created_at_human' => $notif->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unreadCount' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }
}
