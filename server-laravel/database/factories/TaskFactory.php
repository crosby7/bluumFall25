<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->randomElement([
                'Exersice',
                'Take your Medicine',
                'Read for 15 minutes',
            ]), // e.g. "Iron Sword"
            'description' => '',
            'xp_value' => 100,
            'gem_value' => 100,
        ];
    }
}
