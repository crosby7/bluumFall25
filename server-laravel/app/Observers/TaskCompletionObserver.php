<?php

namespace App\Observers;

use App\Enums\TaskStatus;
use App\Models\TaskCompletion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskCompletionObserver
{
    /**
     * Handle the TaskCompletion "updated" event.
     *
     * Automatically distributes gems and XP to the patient when a task
     * is marked as completed.
     *
     * @param TaskCompletion $completion
     * @return void
     */
    public function updated(TaskCompletion $completion): void
    {
        // Only distribute rewards if status just changed to COMPLETED
        if (!$this->shouldDistributeRewards($completion)) {
            return;
        }

        try {
            $this->distributeRewards($completion);
        } catch (\Exception $e) {
            Log::error('Failed to distribute rewards for TaskCompletion', [
                'task_completion_id' => $completion->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Determine if rewards should be distributed for this completion.
     *
     * @param TaskCompletion $completion
     * @return bool
     */
    protected function shouldDistributeRewards(TaskCompletion $completion): bool
    {
        // Check if status attribute changed
        if (!$completion->wasChanged('status')) {
            return false;
        }

        // Check if new status is COMPLETED
        if ($completion->status !== TaskStatus::COMPLETED) {
            return false;
        }

        return true;
    }

    /**
     * Distribute gems and XP rewards to the patient.
     *
     * @param TaskCompletion $completion
     * @return void
     */
    protected function distributeRewards(TaskCompletion $completion): void
    {
        DB::transaction(function () use ($completion) {
            // Load the subscription with patient and task relationships
            $subscription = $completion->subscription;

            if (!$subscription) {
                Log::warning('TaskCompletion has no subscription', [
                    'task_completion_id' => $completion->id,
                ]);
                return;
            }

            $patient = $subscription->patient;
            $task = $subscription->task;

            // Validate that both patient and task exist
            if (!$patient) {
                Log::warning('TaskSubscription has no patient', [
                    'task_completion_id' => $completion->id,
                    'subscription_id' => $subscription->id,
                ]);
                return;
            }

            if (!$task) {
                Log::warning('TaskSubscription has no task', [
                    'task_completion_id' => $completion->id,
                    'subscription_id' => $subscription->id,
                ]);
                return;
            }

            // Get reward values (default to 0 if null)
            $xpValue = $task->xp_value ?? 0;
            $gemValue = $task->gem_value ?? 0;

            // Award the rewards to the patient
            $patient->increment('experience', $xpValue);
            $patient->increment('gems', $gemValue);

            Log::info('Rewards distributed to patient', [
                'patient_id' => $patient->id,
                'task_id' => $task->id,
                'task_completion_id' => $completion->id,
                'xp_awarded' => $xpValue,
                'gems_awarded' => $gemValue,
                'total_experience' => $patient->experience,
                'total_gems' => $patient->gems,
            ]);
        });
    }
}
