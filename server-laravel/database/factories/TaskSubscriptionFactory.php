<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskSubscription>
 */
class TaskSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'task_id' => Task::factory(),
            'start_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'scheduled_time' => $this->faker->time('H:i:s'), // Random time of day (e.g., 08:00:00, 14:30:00, 21:00:00)
            'interval_days' => $this->faker->randomElement([1, 2, 7, 14, 30]),
            'timezone' => $this->faker->randomElement(['UTC', 'America/New_York', 'America/Chicago', 'America/Los_Angeles', 'Europe/London']),
            'is_active' => $this->faker->boolean(80), // 80% active
        ];
    }

    /**
     * Indicate that the subscription is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the subscription is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
