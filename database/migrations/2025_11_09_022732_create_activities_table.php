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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('activity_categories')->onDelete('cascade');
            $table->enum('type', ['umum', 'khusus'])->default('umum');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->integer('max_participants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_attendance_confirmation')->default(true);
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['type', 'is_active']);
            $table->index(['start_date', 'end_date']);
            $table->index('status');
            $table->index('category_id');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};