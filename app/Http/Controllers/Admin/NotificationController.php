<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with(['user', 'activity'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Notification::count(),
            'pending' => Notification::pending()->count(),
            'sent' => Notification::sent()->count(),
            'failed' => Notification::failed()->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    public function create()
    {
        $users = User::warga()->active()->get();
        $activities = Activity::upcoming()->published()->get();
        return view('admin.notifications.create', compact('users', 'activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'activity_id' => 'nullable|exists:activities,id',
            'channel' => 'required|in:web,email,whatsapp',
            'scheduled_at' => 'nullable|date',
            'data' => 'nullable|array',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            Notification::create([
                'type' => $validated['type'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'user_id' => $userId,
                'activity_id' => $validated['activity_id'] ?? null,
                'channel' => $validated['channel'],
                'scheduled_at' => $validated['scheduled_at'] ?? now(),
                'data' => $validated['data'] ?? [],
                'status' => 'pending',
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dibuat.');
    }

    public function show(Notification $notification)
    {
        $notification->load(['user', 'activity', 'relatedUser']);
        return view('admin.notifications.show', compact('notification'));
    }

    public function retry(Notification $notification)
    {
        if ($notification->canBeRetried()) {
            $notification->retry();
            return back()->with('success', 'Notifikasi akan dicoba kembali.');
        }

        return back()->with('error', 'Notifikasi tidak dapat dicoba kembali.');
    }

    public function bulkRetry(Request $request)
    {
        $request->validate([
            'notifications' => 'required|array',
            'notifications.*' => 'exists:notifications,id',
        ]);

        Notification::whereIn('id', $request->notifications)
            ->failed()
            ->get()
            ->each->retry();

        return back()->with('success', 'Notifikasi gagal akan dicoba kembali.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}
