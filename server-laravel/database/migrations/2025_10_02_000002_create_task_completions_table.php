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
        Schema::create('task_completions', function (Blueprint $table) {
            $table->id();

            // Foreign Key
            $table->unsignedBigInteger('subscription_id');

            // Instance fields
            $table->timestamp('scheduled_for');
            $table->timestamp('completed_at')->nullable();
            $table->string('status')->default('pending');

            // Timestamps
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('subscription_id')->references('id')->on('task_subscriptions')->onDelete('cascade');

            // Indexes for performance
            $table->index(['subscription_id', 'scheduled_for']);
            $table->index(['status', 'scheduled_for']);
            $table->index(['subscription_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_completions');
    }
};
