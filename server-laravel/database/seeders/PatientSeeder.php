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
        // Clear existing patient data and related records
        TaskCompletion::truncate();
        TaskSubscription::truncate();
        Patient::truncate();

        // Create two patients
        $patient1 = Patient::factory()->create([
            'username' => 'FluffyCat',
            'avatar_id' => 1,
            'experience' => 50,
            'gems' => 1000,
            'pairing_code' => '111111',
        ]);

        $patient2 = Patient::factory()->create([
            'username' => 'CuddlyBear',
            'avatar_id' => 1,
            'experience' => 50,
            'gems' => 1000,
            'pairing_code' => '222222',
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

        // Apply varied statuses to both patients
        foreach ($patients as $patientIndex => $patient) {
            $completions = TaskCompletion::whereHas('subscription', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })->get();

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
