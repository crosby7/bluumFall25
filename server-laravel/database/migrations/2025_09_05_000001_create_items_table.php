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
        Schema::create('items', function (Blueprint $table) {
            // Primary Key
            $table->id('id');

            // Simplified column names
            $table->string('name', 50);
            $table->string('description', 200)->nullable();
            $table->integer('price');
            $table->string('image', 200);

            // Enum for categories
            $table->enum('category', [
                'Hat',
                'Eyewear',
                'Shirt',
                'Footwear',
                'Neckwear',
                'Wallpaper',
                'Carpet',
                'Window',
                'Room Item'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
