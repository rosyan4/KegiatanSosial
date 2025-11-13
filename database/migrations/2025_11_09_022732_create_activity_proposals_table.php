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
        Schema::create('activity_proposals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('objectives')->nullable(); // tujuan kegiatan
            $table->text('benefits')->nullable(); // manfaat untuk warga
            $table->date('proposed_date');
            $table->string('proposed_location');
            $table->integer('estimated_participants')->nullable();
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->text('required_support')->nullable(); // dukungan yang dibutuhkan dari RT
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'need_revision'])->default('pending');
            $table->text('admin_notes')->nullable(); // catatan dari admin
            $table->text('rejection_reason')->nullable(); // alasan penolakan
            $table->foreignId('proposed_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('proposed_date');
            $table->index('proposed_by');
            $table->index('reviewed_by');
            $table->index(['status', 'proposed_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_proposals');
    }
};