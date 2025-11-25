<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan daftar notifikasi
     */
    public function index()
    {
        $notifications = Notification::with(['user', 'activity'])
            ->latest()
            ->paginate(15);

        // Statistik status
        $stats = [
            'total' => Notification::count(),
            'pending' => Notification::where('status', 'pending')->count(),
            'sent' => Notification::where('status', 'sent')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Form buat notifikasi baru
     */
    public function create()
    {
        $users = User::warga()->active()->get();
        $activities = Activity::upcoming()->published()->get();
        return view('admin.notifications.create', compact('users', 'activities'));
    }

    /**
     * Simpan notifikasi
     */
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
            $notification = Notification::create([
                'type' => $validated['type'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'user_id' => $userId,
                'activity_id' => $validated['activity_id'] ?? null,
                'channel' => $validated['channel'],
                'scheduled_at' => $validated['scheduled_at'] ?? now(),
                'data' => $validated['data'] ?? [],
                'status' => 'pending', // status awal selalu pending
            ]);

            // Jika notifikasi dikirim langsung (tanpa jadwal atau jadwal <= sekarang)
            if (!$validated['scheduled_at'] || $validated['scheduled_at'] <= now()) {
                // Di sini biasanya memanggil fungsi kirim notifikasi
                // Contoh sederhana:
                // NotificationService::send($notification);

                // Ubah status menjadi sent setelah dikirim
                $notification->update(['status' => 'sent']);
            }
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dibuat.');
    }

    /**
     * Tampilkan detail notifikasi
     */
    public function show(Notification $notification)
    {
        $notification->load(['user', 'activity', 'relatedUser']);
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Retry notifikasi gagal
     */
    public function retry(Notification $notification)
    {
        if ($notification->canBeRetried()) {
            $notification->retry();
            return back()->with('success', 'Notifikasi akan dicoba kembali.');
        }

        return back()->with('error', 'Notifikasi tidak dapat dicoba kembali.');
    }

    /**
     * Retry multiple notifikasi gagal
     */
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

    /**
     * Hapus notifikasi
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}
