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
        Schema::table('patients', function (Blueprint $table) {
            // First, make the column nullable to handle existing records
            $table->unsignedBigInteger('avatar_id')->nullable()->change();

            // Add the foreign key constraint
            $table->foreign('avatar_id')->references('id')->on('avatars')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['avatar_id']);

            // Revert to non-nullable
            $table->unsignedBigInteger('avatar_id')->nullable(false)->change();
        });
    }
};
