<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'user_id'   => 1, // or ->inRandomOrder()->first()->id
            'item_id'   => Item::inRandomOrder()->first()?->id ?? Item::factory(),
            'equipped' => $this->faker->boolean(25), // ~25% chance equipped
        ];
    }
}
