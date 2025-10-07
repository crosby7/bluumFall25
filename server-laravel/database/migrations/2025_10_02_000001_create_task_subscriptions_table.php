<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_subscriptions', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('task_id');

            // Scheduling fields
            $table->timestamp('start_at');
            $table->integer('interval_days')->default(1);
            $table->string('timezone', 64)->default('UTC');
            $table->boolean('is_active')->default(true);

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

            // Indexes
            $table->index(['patient_id', 'is_active']);
            $table->index(['task_id', 'is_active']);
        });

        // Partial unique index: only one active subscription per patient-task pair
        // This allows multiple inactive subscriptions but only one active one
        // SQLite uses 1 for true, other databases use true
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('CREATE UNIQUE INDEX task_subscriptions_patient_task_active_unique ON task_subscriptions (patient_id, task_id) WHERE is_active = 1');
        } else {
            DB::statement('CREATE UNIQUE INDEX task_subscriptions_patient_task_active_unique ON task_subscriptions (patient_id, task_id) WHERE is_active = true');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS task_subscriptions_patient_task_active_unique');
        Schema::dropIfExists('task_subscriptions');
    }
};
