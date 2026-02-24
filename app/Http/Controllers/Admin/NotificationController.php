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
}
