<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Patients Table
 *
 * Stores patient accounts (the primary users of the tablet app). Patients earn experience points
 * and gems by completing tasks, which they can use to customize their avatar. Patients authenticate
 * using a pairing code that nurses provide when initializing them on a mobile device.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('pairing_code', 6)->unique();
            $table->timestamp('paired_at')->nullable();
            $table->string('device_identifier')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('avatar_id');
            $table->integer('experience')->default(0);
            $table->integer('gems')->default(0);
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
        Schema::dropIfExists('sessions');
    }
};
