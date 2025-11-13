<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Models\ActivityProposal;
use App\Models\AttendanceConfirmation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_activities' => Activity::count(),
            'total_users' => User::warga()->count(),
            'pending_proposals' => ActivityProposal::pending()->count(),
            'upcoming_activities' => Activity::upcoming()->count(),
            'ongoing_activities' => Activity::ongoing()->count(),
            'recent_activities' => Activity::with('category', 'creator')
                ->latest()
                ->limit(5)
                ->get(),
            'attendance_stats' => $this->getAttendanceStats(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function getAttendanceStats()
    {
        $activities = Activity::completed()->get();

        return [
            'total_participants' => AttendanceConfirmation::hadir()->count(),
            'average_attendance' => $activities->avg(function($activity) {
                return $activity->getAttendancePercentage();
            }) ?? 0,
            'total_events' => $activities->count(),
        ];
    }
}