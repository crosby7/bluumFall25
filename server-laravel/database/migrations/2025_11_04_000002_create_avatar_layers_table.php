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
        Schema::create('avatar_layers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avatar_id');
            $table->integer('layer_number');
            $table->string('layer_name', 50);
            $table->string('image_path', 200);
            $table->timestamps();

            $table->foreign('avatar_id')->references('id')->on('avatars')->onDelete('cascade');

            // Ensure unique layer numbers per avatar
            $table->unique(['avatar_id', 'layer_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avatar_layers');
    }
};
