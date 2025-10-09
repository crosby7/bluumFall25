<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\TaskSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCompletionPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a patient can update their own task completion.
     */
    public function test_patient_can_update_own_task_completion(): void
    {
        // Create a patient with a task completion
        $patient = Patient::factory()->create();
        $task = Task::factory()->create();
        $subscription = TaskSubscription::factory()->create([
            'patient_id' => $patient->id,
            'task_id' => $task->id,
        ]);
        $completion = TaskCompletion::factory()->create([
            'subscription_id' => $subscription->id,
            'status' => TaskStatus::PENDING,
        ]);

        // Authenticate as the patient
        $response = $this->actingAs($patient, 'sanctum')
            ->putJson("/api/nurse/task-completions/{$completion->id}", [
                'status' => 'completed',
                'completed_at' => now()->toISOString(),
            ]);

        $response->assertStatus(200);
        $this->assertEquals(TaskStatus::COMPLETED, $completion->fresh()->status);
    }

    /**
     * Test that a patient cannot update another patient's task completion.
     */
    public function test_patient_cannot_update_other_patients_task_completion(): void
    {
        // Create two patients
        $patient1 = Patient::factory()->create();
        $patient2 = Patient::factory()->create();

        $task = Task::factory()->create();
        $subscription = TaskSubscription::factory()->create([
            'patient_id' => $patient2->id,
            'task_id' => $task->id,
        ]);
        $completion = TaskCompletion::factory()->create([
            'subscription_id' => $subscription->id,
            'status' => TaskStatus::PENDING,
        ]);

        // Authenticate as patient1, try to update patient2's task
        $response = $this->actingAs($patient1, 'sanctum')
            ->putJson("/api/nurse/task-completions/{$completion->id}", [
                'status' => 'completed',
                'completed_at' => now()->toISOString(),
            ]);

        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test that a nurse can update any task completion.
     */
    public function test_nurse_can_update_any_task_completion(): void
    {
        $nurse = Nurse::factory()->create();
        $patient = Patient::factory()->create();
        $task = Task::factory()->create();
        $subscription = TaskSubscription::factory()->create([
            'patient_id' => $patient->id,
            'task_id' => $task->id,
        ]);
        $completion = TaskCompletion::factory()->create([
            'subscription_id' => $subscription->id,
            'status' => TaskStatus::PENDING,
        ]);

        // Authenticate as the nurse
        $response = $this->actingAs($nurse, 'web')
            ->putJson("/api/nurse/task-completions/{$completion->id}", [
                'status' => 'completed',
                'completed_at' => now()->toISOString(),
            ]);

        $response->assertStatus(200);
        $this->assertEquals(TaskStatus::COMPLETED, $completion->fresh()->status);
    }

    /**
     * Test that a patient cannot delete a task completion.
     */
    public function test_patient_cannot_delete_task_completion(): void
    {
        $patient = Patient::factory()->create();
        $task = Task::factory()->create();
        $subscription = TaskSubscription::factory()->create([
            'patient_id' => $patient->id,
            'task_id' => $task->id,
        ]);
        $completion = TaskCompletion::factory()->create([
            'subscription_id' => $subscription->id,
        ]);

        // Authenticate as the patient and try to delete
        $response = $this->actingAs($patient, 'sanctum')
            ->deleteJson("/api/nurse/task-completions/{$completion->id}");

        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseHas('task_completions', ['id' => $completion->id]);
    }

    /**
     * Test that a nurse can delete a task completion.
     */
    public function test_nurse_can_delete_task_completion(): void
    {
        $nurse = Nurse::factory()->create();
        $patient = Patient::factory()->create();
        $task = Task::factory()->create();
        $subscription = TaskSubscription::factory()->create([
            'patient_id' => $patient->id,
            'task_id' => $task->id,
        ]);
        $completion = TaskCompletion::factory()->create([
            'subscription_id' => $subscription->id,
        ]);

        // Authenticate as the nurse and delete
        $response = $this->actingAs($nurse, 'web')
            ->deleteJson("/api/nurse/task-completions/{$completion->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('task_completions', ['id' => $completion->id]);
    }

    /**
     * Test that a patient cannot create task completions.
     */
    public function test_patient_cannot_create_task_completion(): void
    {
        $patient = Patient::factory()->create();
        $task = Task::factory()->create();
        $subscription = TaskSubscription::factory()->create([
            'patient_id' => $patient->id,
            'task_id' => $task->id,
        ]);

        // Authenticate as patient and try to create
        $response = $this->actingAs($patient, 'sanctum')
            ->postJson('/api/nurse/task-completions', [
                'subscription_id' => $subscription->id,
                'scheduled_for' => now()->toISOString(),
                'status' => 'pending',
            ]);

        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test that a nurse can create task completions.
     */
    public function test_nurse_can_create_task_completion(): void
    {
        $nurse = Nurse::factory()->create();
        $patient = Patient::factory()->create();
        $task = Task::factory()->create();
        $subscription = TaskSubscription::factory()->create([
            'patient_id' => $patient->id,
            'task_id' => $task->id,
        ]);

        // Authenticate as nurse and create
        $response = $this->actingAs($nurse, 'web')
            ->postJson('/api/nurse/task-completions', [
                'subscription_id' => $subscription->id,
                'scheduled_for' => now()->toISOString(),
                'status' => 'pending',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('task_completions', [
            'subscription_id' => $subscription->id,
        ]);
    }
}
