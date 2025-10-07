<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientItem>
 */
class PatientItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id'   => 1,
            'item_id'   => Item::factory(),
            'equipped' => $this->faker->boolean(25), // ~25% chance equipped
        ];
    }
}
