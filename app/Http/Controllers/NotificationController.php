<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::forUser($user->id)
            ->unread()
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        if ($notification->isUnread()) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAsUnread(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsUnread();

        return response()->json(['success' => true]);
    }
}