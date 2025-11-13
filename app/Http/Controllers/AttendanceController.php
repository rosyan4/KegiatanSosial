<?php

namespace App\Http\Controllers;

use App\Models\AttendanceConfirmation;
use App\Models\AttendanceLog;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function myAttendances()
    {
        $user = Auth::user();

        $confirmations = AttendanceConfirmation::with(['activity.category'])
            ->where('user_id', $user->id)
            ->whereHas('activity', function($query) {
                $query->where('end_date', '<', now());
            })
            ->orderBy('confirmed_at', 'desc')
            ->paginate(15);

        $attendanceStats = [
            'total' => AttendanceConfirmation::where('user_id', $user->id)->count(),
            'hadir' => AttendanceConfirmation::where('user_id', $user->id)
                        ->where('status', 'hadir')->count(),
            'tidak_hadir' => AttendanceConfirmation::where('user_id', $user->id)
                            ->where('status', 'tidak_hadir')->count(),
        ];

        return view('attendances.history', compact('confirmations', 'attendanceStats'));
    }

    public function checkIn(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // Validasi akses
        if ($activity->type === 'khusus' && !$activity->canUserJoin($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak diundang untuk kegiatan ini.'
            ], 403);
        }

        // Cek apakah sudah check-in
        $existingLog = AttendanceLog::where('activity_id', $activity->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingLog && $existingLog->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check-in untuk kegiatan ini.'
            ]);
        }

        try {
            if ($existingLog) {
                // Update existing log
                $existingLog->update([
                    'check_in_time' => now(),
                    'check_in_method' => 'website',
                    'check_in_data' => [
                        'device' => $request->userAgent(),
                        'ip' => $request->ip(),
                    ],
                    'status' => 'hadir',
                ]);
            } else {
                // Create new log
                AttendanceLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'check_in_time' => now(),
                    'check_in_method' => 'website',
                    'check_in_data' => [
                        'device' => $request->userAgent(),
                        'ip' => $request->ip(),
                    ],
                    'status' => 'hadir',
                    'recorded_by' => $user->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat check-in.'
            ], 500);
        }
    }

    public function checkOut(Request $request, Activity $activity)
    {
        $user = Auth::user();

        $log = AttendanceLog::where('activity_id', $activity->id)
            ->where('user_id', $user->id)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->first();

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum check-in atau sudah check-out.'
            ]);
        }

        try {
            $log->update([
                'check_out_time' => now(),
                'check_out_method' => 'website',
                'check_out_data' => [
                    'device' => $request->userAgent(),
                    'ip' => $request->ip(),
                ],
                'duration_minutes' => $log->check_in_time->diffInMinutes(now()),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil!',
                'duration' => $log->getDurationFormatted()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat check-out.'
            ], 500);
        }
    }

    public function getAttendanceStatus(Activity $activity)
    {
        $user = Auth::user();

        $log = AttendanceLog::where('activity_id', $activity->id)
            ->where('user_id', $user->id)
            ->first();

        $confirmation = AttendanceConfirmation::where('activity_id', $activity->id)
            ->where('user_id', $user->id)
            ->first();

        $canCheckIn = !$log || !$log->check_in_time;
        $canCheckOut = $log && $log->check_in_time && !$log->check_out_time;

        return response()->json([
            'checked_in' => $log ? ($log->check_in_time !== null) : false,
            'checked_out' => $log ? ($log->check_out_time !== null) : false,
            'check_in_time' => $log && $log->check_in_time ? $log->check_in_time->format('H:i') : null,
            'check_out_time' => $log && $log->check_out_time ? $log->check_out_time->format('H:i') : null,
            'attendance_status' => $log ? $log->status : null,
            'confirmation_status' => $confirmation ? $confirmation->status : null,
            'can_check_in' => $canCheckIn,
            'can_check_out' => $canCheckOut,
        ]);
    }
}