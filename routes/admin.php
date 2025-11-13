<?php

use App\Http\Controllers\Admin\{
    AuthController,
    ActivityController,
    ActivityCategoryController,
    UserManagementController,
    ActivityProposalController,
    DocumentationController,
    AttendanceController,
    NotificationController,
    SettingController,
    DashboardController
};
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activities
    Route::get('/activities/calendar', [ActivityController::class, 'calendar'])->name('activities.calendar');
    Route::resource('activities', ActivityController::class);
    Route::post('activities/{activity}/publish', [ActivityController::class, 'publish'])->name('activities.publish');
    Route::post('activities/{activity}/cancel', [ActivityController::class, 'cancel'])->name('activities.cancel');
    Route::post('activities/{activity}/complete', [ActivityController::class, 'complete'])->name('activities.complete');

    // Categories
    Route::resource('categories', ActivityCategoryController::class);
    Route::post('categories/{category}/toggle-status', [ActivityCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // Users
    Route::resource('users', UserManagementController::class);
    Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulk-action');

    // Proposals
    Route::resource('proposals', ActivityProposalController::class)->only(['index', 'show']);
    Route::get('proposals/{proposal}/review', [ActivityProposalController::class, 'review'])->name('proposals.review');
    Route::post('proposals/{proposal}/mark-under-review', [ActivityProposalController::class, 'markUnderReview'])->name('proposals.mark-under-review');
    Route::post('proposals/{proposal}/approve', [ActivityProposalController::class, 'approve'])->name('proposals.approve');
    Route::post('proposals/{proposal}/reject', [ActivityProposalController::class, 'reject'])->name('proposals.reject');
    Route::post('proposals/{proposal}/request-revision', [ActivityProposalController::class, 'requestRevision'])->name('proposals.request-revision');
    Route::post('proposals/bulk-action', [ActivityProposalController::class, 'bulkAction'])->name('proposals.bulk-action');

    // Documentations
    Route::resource('documentations', DocumentationController::class);
    Route::post('documentations/{documentation}/publish', [DocumentationController::class, 'publish'])->name('documentations.publish');
    Route::post('documentations/{documentation}/unpublish', [DocumentationController::class, 'unpublish'])->name('documentations.unpublish');
    Route::delete('documentations/{documentation}/gallery/{imageIndex}', [DocumentationController::class, 'removeGalleryImage'])->name('documentations.remove-gallery-image');

    // Attendance
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/{activity}', [AttendanceController::class, 'showActivity'])->name('attendance.show');
    Route::post('attendance/{activity}/manual-checkin', [AttendanceController::class, 'manualCheckIn'])->name('attendance.manual-checkin');
    Route::post('attendance/logs/{log}/manual-checkout', [AttendanceController::class, 'manualCheckOut'])->name('attendance.manual-checkout');
    Route::post('attendance/confirmations/{confirmation}/update', [AttendanceController::class, 'updateConfirmation'])->name('attendance.update-confirmation');
    Route::post('attendance/logs/{log}/verify', [AttendanceController::class, 'verifyAttendance'])->name('attendance.verify');
    Route::post('attendance/{activity}/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('attendance/{activity}/export', [AttendanceController::class, 'exportAttendance'])->name('attendance.export');
    Route::get('attendance-reports', [AttendanceController::class, 'reports'])->name('attendance.reports');

    // Notifications
    Route::post('notifications/{notification}/retry', [NotificationController::class, 'retry'])->name('notifications.retry');
    Route::post('notifications/bulk-retry', [NotificationController::class, 'bulkRetry'])->name('notifications.bulk-retry');
    Route::post('notifications/{activity}/send-reminders', [NotificationController::class, 'sendActivityReminders'])->name('notifications.send-reminders');
    Route::post('notifications/send-invitation-reminders', [NotificationController::class, 'sendInvitationReminders'])->name('notifications.send-invitation-reminders');

    Route::resource('notifications', NotificationController::class)->except(['edit', 'update']);


    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/{group}', [SettingController::class, 'updateGroup'])->name('settings.update-group');
    Route::post('settings/initialize-defaults', [SettingController::class, 'initializeDefaults'])->name('settings.initialize-defaults');

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
