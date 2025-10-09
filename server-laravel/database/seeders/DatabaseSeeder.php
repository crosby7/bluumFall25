<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Item;
use App\Models\PatientItem;
use App\Models\Task;
use App\Models\TaskSubscription;
use App\Models\TaskCompletion;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call NurseSeeder first
        $this->call(NurseSeeder::class);

        Patient::factory()->create([
            'username' => 'devpatient',
            'pairing_code' => '123456',
        ]);
        Patient::factory()->create([
            'username' => 'jared',
            'pairing_code' => '654321',
        ]);
        //Patient::factory(10)->create();

        // Seed items from predefined list
        $this->call(ItemSeeder::class);
        PatientItem::factory(10)->create();

        // Seed task templates from predefined list
        $this->call(TaskTemplateSeeder::class);

        // Create unique active task subscriptions for each patient-task combination
        $patients = Patient::all();
        $tasks = Task::all();

        // Create some active subscriptions ensuring uniqueness
        $subscriptionsCreated = 0;
        foreach ($patients->take(3) as $patient) {
            foreach ($tasks->take(2) as $task) {
                TaskSubscription::factory()->active()->create([
                    'patient_id' => $patient->id,
                    'task_id' => $task->id,
                ]);
                $subscriptionsCreated++;
            }
        }

        // Create some inactive subscriptions (duplicates are OK for inactive)
        foreach (range(1, 3) as $i) {
            TaskSubscription::factory()->inactive()->create([
                'patient_id' => $patients->random()->id,
                'task_id' => $tasks->random()->id,
            ]);
        }

        // Create specific task completions for dev patient with various statuses for testing
        $devPatient = Patient::where('username', 'devpatient')->first();
        $devSubscriptions = TaskSubscription::where('patient_id', $devPatient->id)->get();

        foreach ($devSubscriptions as $subscription) {
            // Today's pending tasks
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->addHours(rand(8, 12)),
                'status' => \App\Enums\TaskStatus::PENDING,
                'completed_at' => null,
            ]);

            // Today's completed tasks
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->addHours(rand(6, 10)),
                'status' => \App\Enums\TaskStatus::COMPLETED,
                'completed_at' => now()->subHours(rand(1, 3)),
            ]);

            // Overdue tasks (pending but scheduled in the past)
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->subDays(1)->addHours(rand(8, 18)),
                'status' => \App\Enums\TaskStatus::PENDING,
                'completed_at' => null,
            ]);

            // Yesterday's completed tasks
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->subDays(1)->addHours(rand(8, 18)),
                'status' => \App\Enums\TaskStatus::COMPLETED,
                'completed_at' => today()->subDays(1)->addHours(rand(10, 20)),
            ]);

            // Skipped tasks
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->subDays(rand(1, 3))->addHours(rand(8, 18)),
                'status' => \App\Enums\TaskStatus::SKIPPED,
                'completed_at' => null,
            ]);

            // Failed tasks
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->subDays(rand(1, 2))->addHours(rand(8, 18)),
                'status' => \App\Enums\TaskStatus::FAILED,
                'completed_at' => null,
            ]);

            // Future pending tasks
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->addDays(1)->addHours(rand(8, 18)),
                'status' => \App\Enums\TaskStatus::PENDING,
                'completed_at' => null,
            ]);
        }

        // Create task completions for existing subscriptions (random dates)
        $allSubscriptions = TaskSubscription::all();

        foreach (range(1, 15) as $i) {
            TaskCompletion::factory()->completed()->create([
                'subscription_id' => $allSubscriptions->random()->id,
            ]);
        }

        foreach (range(1, 10) as $i) {
            TaskCompletion::factory()->pending()->create([
                'subscription_id' => $allSubscriptions->random()->id,
            ]);
        }

        foreach (range(1, 5) as $i) {
            TaskCompletion::factory()->skipped()->create([
                'subscription_id' => $allSubscriptions->random()->id,
            ]);
        }
    }
}
