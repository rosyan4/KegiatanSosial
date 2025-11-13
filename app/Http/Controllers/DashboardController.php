<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AttendanceConfirmation;
use App\Models\Invitation;
use App\Models\ActivityProposal;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data untuk dashboard
        $upcomingActivities = Activity::where('status', 'published')
            ->where('start_date', '>', now())
            ->where(function($query) use ($user) {
                $query->where('type', 'umum')
                    ->orWhereHas('invitations', function($q) use ($user) {
                        $q->where('user_id', $user->id)
                          ->where('status', 'accepted');
                    });
            })
            ->with(['category', 'attendanceConfirmations' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        $pendingInvitations = Invitation::with('activity')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereHas('activity', function($q) {
                $q->where('end_date', '>', now())
                  ->where('status', 'published');
            })
            ->count();

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $myProposals = ActivityProposal::where('proposed_by', $user->id)
            ->count();

        $attendanceStats = [
            'confirmed' => AttendanceConfirmation::where('user_id', $user->id)
                ->where('status', 'hadir')
                ->count(),
            'pending' => AttendanceConfirmation::where('user_id', $user->id)
                ->whereNull('confirmed_at')
                ->count(),
        ];

        return view('dashboard', compact(
            'upcomingActivities',
            'pendingInvitations',
            'unreadNotifications',
            'myProposals',
            'attendanceStats'
        ));
    }
}