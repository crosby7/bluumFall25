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
        Patient::factory()->create([
            'username' => 'devpatient',
            'email' => 'dev@example.com',
        ]);
        Patient::factory()->create([
            'username' => 'jared',
            'email' => 'jared@example.com',
        ]);
        Patient::factory()->create([
            'username' => 'jace',
            'email' => 'jace@example.com',
        ]);
        Patient::factory()->create([
            'username' => 'lilliana',
            'email' => 'zombiez@example.com',
        ]);
        //Patient::factory(10)->create();

        Item::factory(50)->create();
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
        TaskSubscription::factory(3)->inactive()->create();

        // Create specific task completions for dev patient TODAY for testing
        $devPatient = Patient::where('email', 'dev@example.com')->first();
        $devSubscriptions = TaskSubscription::where('patient_id', $devPatient->id)->get();

        foreach ($devSubscriptions as $subscription) {
            // Create a task scheduled for today
            TaskCompletion::factory()->create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => today()->addHours(rand(8, 18)),
                'status' => \App\Enums\TaskStatus::PENDING,
                'completed_at' => null,
            ]);
        }

        // Create task completions for existing subscriptions (random dates)
        TaskCompletion::factory(15)->completed()->create();
        TaskCompletion::factory(10)->pending()->create();
        TaskCompletion::factory(5)->skipped()->create();
    }
}
