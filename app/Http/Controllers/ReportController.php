<?php

namespace App\Http\Controllers;

use App\Models\AttendanceConfirmation;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function attendanceReport(Request $request)
    {
        $user = Auth::user();

        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Data kehadiran bulanan
        $monthlyAttendances = AttendanceConfirmation::with(['activity'])
            ->where('user_id', $user->id)
            ->whereHas('activity', function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy(function($confirmation) {
                return $confirmation->activity->start_date->format('Y-m');
            })
            ->map(function($monthData) {
                return [
                    'total' => $monthData->count(),
                    'hadir' => $monthData->where('status', 'hadir')->count(),
                    'tidak_hadir' => $monthData->where('status', 'tidak_hadir')->count(),
                    'mungkin' => $monthData->where('status', 'mungkin')->count(),
                ];
            });

        // Statistik tahunan
        $yearlyStats = AttendanceConfirmation::with(['activity'])
            ->where('user_id', $user->id)
            ->whereHas('activity', function($query) use ($year) {
                $query->whereYear('start_date', $year);
            })
            ->get()
            ->groupBy(function($confirmation) {
                return $confirmation->activity->start_date->format('m');
            })
            ->map(function($monthData) {
                return [
                    'hadir' => $monthData->where('status', 'hadir')->count(),
                    'total' => $monthData->count(),
                ];
            });

        // Kegiatan yang dihadiri
        $attendedActivities = Activity::whereHas('attendanceConfirmations', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', 'hadir');
            })
            ->where('end_date', '<', now())
            ->with('category')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('reports.attendance', compact(
            'monthlyAttendances',
            'yearlyStats',
            'attendedActivities',
            'year',
            'month'
        ));
    }

    public function activityParticipation()
    {
        $user = Auth::user();

        // Statistik partisipasi
        $activities = Activity::where('end_date', '<', now())
            ->where('status', 'published')
            ->get();

        $participationStats = [
            'total_activities' => $activities->count(),
            'umum_count' => $activities->where('type', 'umum')->count(),
            'khusus_count' => $activities->where('type', 'khusus')->count(),
            'participated_count' => AttendanceConfirmation::where('user_id', $user->id)->count(),
            'attended_count' => AttendanceConfirmation::where('user_id', $user->id)
                                ->where('status', 'hadir')->count(),
        ];

        // Top categories yang sering diikuti
        $topCategories = Activity::selectRaw('
                activity_categories.name,
                activity_categories.color,
                COUNT(attendance_confirmations.id) as participation_count
            ')
            ->join('activity_categories', 'activities.category_id', '=', 'activity_categories.id')
            ->join('attendance_confirmations', function($join) use ($user) {
                $join->on('activities.id', '=', 'attendance_confirmations.activity_id')
                     ->where('attendance_confirmations.user_id', $user->id)
                     ->where('attendance_confirmations.status', 'hadir');
            })
            ->where('activities.end_date', '<', now())
            ->groupBy('activity_categories.id', 'activity_categories.name', 'activity_categories.color')
            ->orderBy('participation_count', 'desc')
            ->limit(5)
            ->get();

        return view('reports.participation', compact(
            'participationStats',
            'topCategories'
        ));
    }
}