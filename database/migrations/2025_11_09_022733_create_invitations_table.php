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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('custom_message')->nullable(); // pesan khusus untuk undangan
            $table->text('decline_reason')->nullable(); // alasan menolak undangan
            $table->timestamps();

            // Indexes
            $table->index(['activity_id', 'user_id']);
            $table->index('status');
            $table->index('sent_at');
            $table->index('responded_at');
            $table->unique(['activity_id', 'user_id']); // mencegah duplikasi undangan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};