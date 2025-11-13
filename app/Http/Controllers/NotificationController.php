<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = Notification::with(['activity', 'relatedUser'])
            ->forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read when viewing
        if ($notification->isUnread()) {
            $notification->markAsRead();
        }

        $notification->load(['activity', 'relatedUser']);

        return view('notifications.show', compact('notification'));
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

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }
}