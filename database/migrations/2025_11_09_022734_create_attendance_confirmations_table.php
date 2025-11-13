<?php
// database/migrations/2024_01_01_000010_create_attendance_confirmations_table.php

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
        Schema::create('attendance_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['hadir', 'tidak_hadir', 'mungkin'])->default('hadir');
            $table->text('notes')->nullable(); // catatan dari warga
            $table->integer('number_of_guests')->default(0); // jumlah tamu yang dibawa
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('reminded_at')->nullable(); // kapan terakhir diingatkan
            $table->integer('reminder_count')->default(0); // jumlah pengingat yang dikirim
            $table->timestamps();

            // Indexes
            $table->index(['activity_id', 'user_id']);
            $table->index('status');
            $table->index('confirmed_at');
            $table->index('reminded_at');
            $table->unique(['activity_id', 'user_id']); // mencegah duplikasi konfirmasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_confirmations');
    }
};