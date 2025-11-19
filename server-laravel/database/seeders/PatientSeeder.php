<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Task;
use App\Models\TaskSubscription;
use App\Models\TaskCompletion;
use App\Enums\TaskStatus;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create two patients
        $patient1 = Patient::factory()->create([
            'username' => 'FlufflyThing',
            'avatar_id' => 1,
            'experience' => 150,
            'gems' => 100,
        ]);

        $patient2 = Patient::factory()->create([
            'username' => 'CuddlyBear',
            'avatar_id' => 2,
            'experience' => 200,
            'gems' => 150,
        ]);

        $patients = [$patient1, $patient2];

        // Get all tasks
        $tasks = Task::all();

        // Define task schedules with appropriate times
        $taskSchedules = [
            'Morning Medication' => '08:00:00',
            'Grooming' => '09:00:00',
            'School: Reading' => '10:00:00',
            'PT Therapy' => '10:30:00',
            'School: Math' => '11:00:00',
            'Lunch' => '12:00:00',
            'Bandage Change' => '13:00:00',
            'OT Therapy' => '14:00:00',
            'Rec Activity' => '15:00:00',
            'Night Medications' => '20:00:00',
        ];

        // Assign all tasks to both patients
        foreach ($patients as $patient) {
            foreach ($tasks as $task) {
                $scheduledTime = $taskSchedules[$task->name] ?? '12:00:00';

                TaskSubscription::create([
                    'patient_id' => $patient->id,
                    'task_id' => $task->id,
                    'start_at' => Carbon::today(),
                    'scheduled_time' => $scheduledTime,
                    'interval_days' => 1, // Daily tasks
                    'timezone' => 'UTC',
                    'is_active' => true,
                ]);
            }
        }

        // Update some task completions to different statuses for test data
        $completions = TaskCompletion::all();

        if ($completions->count() > 0) {
            // Set some completions to different statuses
            $statusUpdates = [
                TaskStatus::PENDING,
                TaskStatus::PENDING,
                TaskStatus::PENDING,
                TaskStatus::PENDING,
                TaskStatus::COMPLETED,
                TaskStatus::SKIPPED,
                TaskStatus::FAILED,
                TaskStatus::OVERDUE,
            ];

            // Update completions to various statuses
            foreach ($statusUpdates as $index => $status) {
                if ($completions->count() > $index) {
                    $completions[$index]->update(['status' => $status]);

                    // Set completed_at for completed status
                    if ($status === TaskStatus::COMPLETED) {
                        $completions[$index]->update(['completed_at' => Carbon::now()]);
                    }
                }
            }
        }
    }
}
