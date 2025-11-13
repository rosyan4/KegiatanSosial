<?php

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // activity_reminder, new_activity, invitation, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // additional data in JSON format
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('activity_id')->nullable()->constrained('activities')->onDelete('cascade');
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('scheduled_at')->nullable(); // untuk notifikasi terjadwal
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'read', 'failed'])->default('pending');
            $table->string('channel')->default('web'); // web, email, whatsapp
            $table->integer('retry_count')->default(0);
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('channel');
            $table->index('scheduled_at');
            $table->index('sent_at');
            $table->index('read_at');
            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};