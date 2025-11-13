<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AttendanceConfirmation;
use App\Models\AttendanceLog;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function reports(Request $request)
    {
        $query = Activity::completed()
            ->with(['category', 'attendanceConfirmations', 'attendanceLogs'])
            ->where(function($q) {
                $q->whereHas('attendanceConfirmations')
                  ->orWhereHas('attendanceLogs');
            });

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('start_date', '<=', $request->end_date);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $activities = $query->latest()->paginate(15);

        // Overall stats
        $overallStats = $this->getOverallAttendanceStats();

        return view('admin.attendance.reports', compact('activities', 'overallStats'));
    }

    public function showActivity(Activity $activity)
    {
        $activity->load(['attendanceConfirmations.user', 'attendanceLogs.user', 'category']);

        $confirmations = $activity->attendanceConfirmations()
            ->with('user')
            ->get();

        $logs = $activity->attendanceLogs()
            ->with('user')
            ->get();

        $stats = [
            'total' => $logs->count(),
            'hadir' => $confirmations->where('status', 'hadir')->count(),
            'tidak_hadir' => $confirmations->where('status', 'tidak_hadir')->count(),
            'mungkin' => $confirmations->where('status', 'mungkin')->count(),
            'confirmed' => $confirmations->count(),
            'checked_in' => $logs->whereNotNull('check_in_time')->count(),
            'checked_out' => $logs->whereNotNull('check_out_time')->count(),
            'active' => $logs->whereNotNull('check_in_time')->whereNull('check_out_time')->count(),
            'verified' => $logs->where('is_verified', true)->count(),
        ];

        return view('admin.attendance.show', compact('activity', 'confirmations', 'logs', 'stats'));
    }

    public function index()
    {
        $activities = Activity::with(['attendanceConfirmations', 'attendanceLogs'])
            ->where(function($q) {
                $q->whereHas('attendanceConfirmations')
                  ->orWhereHas('attendanceLogs');
            })
            ->latest()
            ->paginate(10);

        return view('admin.attendance.index', compact('activities'));
    }

    public function manualCheckIn(Request $request, Activity $activity)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'check_in_time' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $log = AttendanceLog::create([
            'activity_id' => $activity->id,
            'user_id' => $request->user_id,
            'recorded_by' => auth()->id(),
            'check_in_time' => $request->check_in_time ?? now(),
            'check_in_method' => 'manual',
            'status' => 'hadir',
            'notes' => $request->notes,
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Check-in manual berhasil dicatat.');
    }

    public function manualCheckOut(Request $request, AttendanceLog $log)
    {
        $log->checkOut('manual');

        return back()->with('success', 'Check-out manual berhasil dicatat.');
    }

    public function updateConfirmation(Request $request, AttendanceConfirmation $confirmation)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir,mungkin',
            'notes' => 'nullable|string',
            'number_of_guests' => 'nullable|integer|min:0',
        ]);

        switch ($request->status) {
            case 'hadir':
                $confirmation->confirmHadir($request->notes, $request->number_of_guests ?? 0);
                break;
            case 'tidak_hadir':
                $confirmation->confirmTidakHadir($request->notes);
                break;
            case 'mungkin':
                $confirmation->confirmMungkin($request->notes, $request->number_of_guests ?? 0);
                break;
        }

        return back()->with('success', 'Konfirmasi kehadiran berhasil diperbarui.');
    }

    public function verifyAttendance(AttendanceLog $log)
    {
        $log->verify(auth()->id());

        return back()->with('success', 'Kehadiran berhasil diverifikasi.');
    }

    public function markAttendance(Request $request, Activity $activity)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:hadir,terlambat,tidak_hadir',
            'notes' => 'nullable|string',
        ]);

        AttendanceLog::create([
            'activity_id' => $activity->id,
            'user_id' => $request->user_id,
            'recorded_by' => auth()->id(),
            'status' => $request->status,
            'check_in_time' => $request->status !== 'tidak_hadir' ? now() : null,
            'check_in_method' => 'manual',
            'notes' => $request->notes,
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Kehadiran berhasil dicatat.');
    }

    // PERBAIKAN: Export method yang sudah diimplementasi
    public function exportAttendance(Activity $activity)
    {
        $confirmations = $activity->attendanceConfirmations()
            ->with('user')
            ->get();

        $logs = $activity->attendanceLogs()
            ->with('user')
            ->get();

        // Basic CSV export sebagai fallback
        $fileName = "attendance-{$activity->id}-" . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($confirmations, $logs) {
            $handle = fopen('php://output', 'w');
            
            // Header untuk confirmations
            fputcsv($handle, ['Type', 'User', 'Status', 'Confirmed At', 'Notes', 'Guests']);
            foreach ($confirmations as $confirmation) {
                fputcsv($handle, [
                    'Confirmation',
                    $confirmation->user->name,
                    $confirmation->status,
                    $confirmation->confirmed_at?->format('Y-m-d H:i'),
                    $confirmation->notes,
                    $confirmation->number_of_guests
                ]);
            }

            // Header untuk logs
            fputcsv($handle, ['']);
            fputcsv($handle, ['Type', 'User', 'Status', 'Check In', 'Check Out', 'Duration']);
            foreach ($logs as $log) {
                fputcsv($handle, [
                    'Log',
                    $log->user->name,
                    $log->status,
                    $log->check_in_time?->format('Y-m-d H:i'),
                    $log->check_out_time?->format('Y-m-d H:i'),
                    $log->getDurationFormatted()
                ]);
            }

            fclose($handle);
        }, $fileName);
    }

    private function getOverallAttendanceStats()
    {
        $activities = Activity::completed()->get();

        return [
            'total_activities' => $activities->count(),
            'total_participations' => AttendanceConfirmation::count(),
            'average_attendance_rate' => $activities->avg(function($activity) {
                return $activity->getAttendancePercentage();
            }) ?? 0,
            'total_attendees' => AttendanceConfirmation::hadir()->get()->sum(function($confirmation) {
                return 1 + $confirmation->number_of_guests;
            }),
        ];
    }
}