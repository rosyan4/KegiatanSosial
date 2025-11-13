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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, text, boolean, integer, json, array
            $table->string('group')->default('general');
            $table->string('label');
            $table->text('description')->nullable();
            $table->json('options')->nullable(); // untuk select options
            $table->integer('sort_order')->default(0);
            $table->boolean('is_public')->default(false); // apakah setting bisa diakses public
            $table->boolean('is_encrypted')->default(false); // apakah value perlu di-encrypt
            $table->timestamps();

            // Indexes
            $table->index('key');
            $table->index('group');
            $table->index('type');
            $table->index('is_public');
            $table->index(['group', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};