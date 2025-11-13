<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tasks Table
 *
 * Stores task templates that define activities patients can complete (e.g., "Brush Teeth", "Take Medicine").
 * These are reusable templates with reward values (XP and gems) that get assigned to patients
 * through task_subscriptions. Tasks themselves don't track completion - they're just the definitions.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('category');
            $table->integer('xp_value');
            $table->integer('gem_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
