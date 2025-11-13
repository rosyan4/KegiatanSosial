<?php
// database/migrations/2024_01_01_000011_create_attendance_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade'); // admin yang mencatat
            $table->enum('status', ['hadir', 'terlambat', 'tidak_hadir'])->default('hadir');
            $table->timestamp('check_in_time')->nullable(); // waktu check-in
            $table->timestamp('check_out_time')->nullable(); // waktu check-out
            $table->integer('duration_minutes')->nullable(); // durasi kehadiran dalam menit
            $table->text('notes')->nullable(); // catatan khusus
            $table->string('check_in_method')->default('manual'); // manual, qrcode, nfc, etc.
            $table->string('check_out_method')->nullable();
            $table->json('check_in_data')->nullable(); // data tambahan check-in (location, device info, etc.)
            $table->json('check_out_data')->nullable(); // data tambahan check-out
            $table->boolean('is_verified')->default(false); // apakah kehadiran sudah diverifikasi admin
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['activity_id', 'user_id']);
            $table->index('status');
            $table->index('check_in_time');
            $table->index('check_out_time');
            $table->index('is_verified');
            $table->index('recorded_by');
            $table->index('verified_by');
            $table->index(['activity_id', 'check_in_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};