<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\User;
use App\Models\ActivityProposal;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['category', 'creator']);

        // Filters
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $activities = $query->latest()->paginate(10);
        $categories = ActivityCategory::active()->get(); // Tambahkan ini

        return view('admin.activities.index', compact('activities', 'categories'));
    }

    public function create()
    {
        $categories = ActivityCategory::active()->get();
        $users = User::warga()->active()->get();

        return view('admin.activities.form', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:activity_categories,id',
            'type' => 'required|in:umum,khusus',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'requires_attendance_confirmation' => 'boolean',
            'status' => 'required|in:draft,published,cancelled,completed',
            'invited_users' => 'required_if:type,khusus|array',
            'invited_users.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $activity = Activity::create($validated + [
                'created_by' => auth()->id()
            ]);

            // Perbaikan: Handle invitations dengan benar
            if ($activity->isKhusus() && isset($validated['invited_users'])) {
                foreach ($validated['invited_users'] as $userId) {
                    if (!$activity->isUserInvited($userId)) {
                        $activity->inviteUser($userId);
                    }
                }
            }

            if ($activity->isPublished()) {
                $this->notifyNewActivity($activity);
            }
        });

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function show(Activity $activity)
    {
        $activity->load(['category', 'creator', 'invitations.user', 'attendanceConfirmations.user']);

        $attendanceStats = $activity->getRealTimeAttendanceStats();
        $invitationStats = [
            'accepted' => $activity->getAcceptedInvitationsCount(),
            'declined' => $activity->getDeclinedInvitationsCount(),
            'pending' => $activity->getPendingInvitationsCount(),
        ];

        return view('admin.activities.show', compact('activity', 'attendanceStats', 'invitationStats'));
    }

    public function edit(Activity $activity)
    {
        $categories = ActivityCategory::active()->get();
        $users = User::warga()->active()->get();

        return view('admin.activities.edit', compact('activity', 'categories', 'users'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:activity_categories,id',
            'type' => 'required|in:umum,khusus',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'requires_attendance_confirmation' => 'boolean',
            'status' => 'required|in:draft,published,cancelled,completed',
            'invited_users' => 'required_if:type,khusus|array',
            'invited_users.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($activity, $validated) {
            $wasDraft = $activity->isDraft();

            $activity->update($validated);

            // Perbaikan: Handle invitations dengan benar
            if ($activity->isKhusus() && isset($validated['invited_users'])) {
                // Remove existing invitations not in new list
                $activity->invitations()
                    ->whereNotIn('user_id', $validated['invited_users'])
                    ->delete();

                // Add new invitations
                foreach ($validated['invited_users'] as $userId) {
                    if (!$activity->isUserInvited($userId)) {
                        $activity->inviteUser($userId);
                    }
                }
            }

            if ($wasDraft && $activity->isPublished()) {
                $this->notifyNewActivity($activity);
            }
        });

        return redirect()->route('admin.activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function publish(Activity $activity)
    {
        $activity->publish();
        $this->notifyNewActivity($activity);

        return back()->with('success', 'Kegiatan berhasil dipublikasikan.');
    }

    public function cancel(Activity $activity)
    {
        $activity->cancel();

        return back()->with('success', 'Kegiatan berhasil dibatalkan.');
    }

    public function complete(Activity $activity)
    {
        $activity->complete();

        return back()->with('success', 'Kegiatan berhasil diselesaikan.');
    }

    // PERBAIKAN: Method notifyNewActivity yang sudah diimplementasi
    private function notifyNewActivity(Activity $activity)
    {
        if ($activity->isUmum()) {
            $users = User::warga()->active()->get();
            foreach ($users as $user) {
                Notification::createNewActivityNotification($user->id, $activity->id);
            }
        } else {
            $invitedUsers = $activity->getInvitedUsers();
            foreach ($invitedUsers as $user) {
                Notification::createNewActivityNotification($user->id, $activity->id);
            }
        }
    }

    public function calendar()
    {
        $activities = Activity::with('category')
            ->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'start_date' => $activity->start_date->format('Y-m-d H:i:s'),
                    'category' => $activity->category,
                    'location' => $activity->location,
                    'status' => $activity->status,
                    'status_label' => $activity->getStatusLabel(),
                ];
            });

        return view('admin.activities.calendar', compact('activities'));
    }
}