<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AttendanceConfirmation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = ActivityCategory::active()->ordered()->get();

        $query = Activity::where('status', 'published')
            ->with(['category', 'creator'])
            ->withCount(['attendanceConfirmations as confirmed_count' => function($q) {
                $q->where('status', 'hadir');
            }])
            ->with(['attendanceConfirmations' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by type
        if ($request->type && in_array($request->type, ['umum', 'khusus'])) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status) {
            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', now());
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                    break;
                case 'completed':
                    $query->where('end_date', '<', now());
                    break;
            }
        }

        $activities = $query->orderBy('start_date')
            ->paginate(12);

        return view('activities.index', compact('activities', 'categories'));
    }

    public function show(Activity $activity)
    {
        $user = Auth::user();

        // Check if user can access khusus activity
        if ($activity->type === 'khusus' && !$activity->isUserInvited($user->id)) {
            abort(403, 'Anda tidak diundang untuk kegiatan ini.');
        }

        $activity->load(['category', 'creator', 'documentation']);

        $userConfirmation = $activity->getUserConfirmation($user->id);
        $isInvited = $activity->isUserInvited($user->id);
        $invitation = $isInvited ? 
            Invitation::where('activity_id', $activity->id)
                ->where('user_id', $user->id)
                ->first() : null;
        
        $attendanceStats = $activity->getAttendanceStats();
        $availableSlots = $activity->getAvailableSlots();

        return view('activities.show', compact(
            'activity',
            'userConfirmation',
            'isInvited',
            'invitation',
            'attendanceStats',
            'availableSlots'
        ));
    }

    public function confirmAttendance(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // Validasi akses
        if ($activity->type === 'khusus' && !$activity->canUserJoin($user->id)) {
            return redirect()->back()->with('error', 'Anda tidak diundang untuk kegiatan ini.');
        }

        $validated = $request->validate([
            'status' => 'required|in:hadir,tidak_hadir,mungkin',
            'notes' => 'nullable|string|max:500',
            'number_of_guests' => 'nullable|integer|min:0|max:5',
        ]);

        // Check jika sudah melebihi kapasitas
        if ($validated['status'] === 'hadir' && !$activity->hasAvailableSlots()) {
            return redirect()->back()->with('error', 'Maaf, kuota peserta untuk kegiatan ini sudah penuh.');
        }

        try {
            // Update or create attendance confirmation
            AttendanceConfirmation::updateOrCreate(
                [
                    'activity_id' => $activity->id,
                    'user_id' => $user->id
                ],
                [
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null,
                    'number_of_guests' => $validated['number_of_guests'] ?? 0,
                    'confirmed_at' => now(),
                ]
            );

            $statusMessage = match($validated['status']) {
                'hadir' => 'Kehadiran berhasil dikonfirmasi!',
                'tidak_hadir' => 'Konfirmasi tidak hadir berhasil disimpan.',
                'mungkin' => 'Status mungkin hadir berhasil disimpan.',
            };

            return redirect()->back()->with('success', $statusMessage);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi kehadiran.');
        }
    }
}