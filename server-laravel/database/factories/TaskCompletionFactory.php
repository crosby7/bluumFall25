<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\TaskSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskCompletion>
 */
class TaskCompletionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([
            TaskStatus::PENDING,
            TaskStatus::COMPLETED,
            TaskStatus::SKIPPED,
            TaskStatus::FAILED,
        ]);

        $scheduledFor = $this->faker->dateTimeBetween('-30 days', '+30 days');

        // If completed, set completed_at to sometime after scheduled_for but not in the future
        $completedAt = null;
        if ($status === TaskStatus::COMPLETED) {
            // Only set completed_at if the task was scheduled in the past
            if ($scheduledFor <= now()) {
                $maxDate = min($scheduledFor->getTimestamp() + 86400, now()->getTimestamp()); // 1 day after or now
                $completedAt = $this->faker->dateTimeBetween($scheduledFor, '@' . $maxDate);
            }
        }

        return [
            'subscription_id' => TaskSubscription::factory(),
            'scheduled_for' => $scheduledFor,
            'completed_at' => $completedAt,
            'status' => $status,
        ];
    }

    /**
     * Indicate that the task is pending.
     */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'scheduled_for' => $this->faker->dateTimeBetween('now', '+30 days'),
                'status' => TaskStatus::PENDING,
                'completed_at' => null,
            ];
        });
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            // Use a past date for scheduled_for to ensure completed_at can be set
            $scheduledFor = $this->faker->dateTimeBetween('-30 days', 'now');
            $maxDate = min($scheduledFor->getTimestamp() + 86400, now()->getTimestamp()); // 1 day after or now

            return [
                'scheduled_for' => $scheduledFor,
                'status' => TaskStatus::COMPLETED,
                'completed_at' => $this->faker->dateTimeBetween($scheduledFor, '@' . $maxDate),
            ];
        });
    }

    /**
     * Indicate that the task is skipped.
     */
    public function skipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::SKIPPED,
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the task failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::FAILED,
            'completed_at' => null,
        ]);
    }
}
