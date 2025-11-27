<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    DashboardController,
    ActivityController,
    InvitationController,
    ActivityProposalController,
    NotificationController,
    DocumentationController,
    AttendanceController,
    CalendarController,
    ReportController,
    SearchController
};

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activities
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
    Route::post('/activities/{activity}/confirm-attendance', [ActivityController::class, 'confirmAttendance'])->name('activities.confirm-attendance');

    // Attendances
    Route::get('/my-attendances', [AttendanceController::class, 'myAttendances'])->name('attendances.my');
    Route::post('/activities/{activity}/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in');
    Route::post('/activities/{activity}/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
    Route::get('/activities/{activity}/attendance-status', [AttendanceController::class, 'getAttendanceStatus'])->name('activities.attendance-status');
    Route::delete('/attendance/{log}/delete', [AttendanceController::class, 'deleteAttendance'])->name('attendance.delete');
    Route::delete('/attendance/confirmation/{confirmation}/delete', [AttendanceController::class, 'deleteConfirmation'])->name('attendance.confirmation.delete');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

    // Invitations
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/invitations/{invitation}', [InvitationController::class, 'show'])->name('invitations.show');
    Route::post('/invitations/{invitation}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

    // Proposals
    Route::get('/proposals', [ActivityProposalController::class, 'index'])->name('proposals.index');
    Route::get('/proposals/create', [ActivityProposalController::class, 'create'])->name('proposals.create');
    Route::post('/proposals', [ActivityProposalController::class, 'store'])->name('proposals.store');
    Route::get('/proposals/{proposal}', [ActivityProposalController::class, 'show'])->name('proposals.show');
    Route::get('/proposals/{proposal}/edit', [ActivityProposalController::class, 'edit'])->name('proposals.edit');
    Route::put('/proposals/{proposal}', [ActivityProposalController::class, 'update'])->name('proposals.update');

    // Documentations
    Route::get('/documentations', [DocumentationController::class, 'index'])->name('documentations.index');
    Route::get('/documentations/{documentation}', [DocumentationController::class, 'show'])->name('documentations.show');

    // Reports
    Route::get('/reports/attendance', [ReportController::class, 'attendanceReport'])->name('reports.attendance');
    Route::get('/reports/participation', [ReportController::class, 'activityParticipation'])->name('reports.participation');

    // Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/{notification}/mark-unread', [NotificationController::class, 'markAsUnread'])->name('mark-unread');
    });


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');

});



Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
