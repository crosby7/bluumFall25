<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\TaskStatus;

class PatientProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'avatar_id' => $this->avatar_id,
            'experience' => $this->experience,
            'gems' => $this->gems,
            'created_at' => $this->created_at,
            'stats' => [
                'total_xp' => $this->experience,
                'total_gems' => $this->gems,
                'active_subscriptions' => $this->taskSubscriptions()->where('is_active', true)->count(),
                'tasks_completed_today' => $this->getTasksCompletedToday(),
                'tasks_completed_this_week' => $this->getTasksCompletedThisWeek(),
                'tasks_completed_total' => $this->getTasksCompletedTotal(),
                'current_streak' => $this->getCurrentStreak(),
            ],
        ];
    }

    /**
     * Get tasks completed today.
     */
    private function getTasksCompletedToday(): int
    {
        return $this->taskCompletions()
            ->where('status', TaskStatus::COMPLETED)
            ->whereDate('completed_at', today())
            ->count();
    }

    /**
     * Get tasks completed this week.
     */
    private function getTasksCompletedThisWeek(): int
    {
        return $this->taskCompletions()
            ->where('status', TaskStatus::COMPLETED)
            ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    /**
     * Get total tasks completed.
     */
    private function getTasksCompletedTotal(): int
    {
        return $this->taskCompletions()
            ->where('status', TaskStatus::COMPLETED)
            ->count();
    }

    /**
     * Calculate current streak (consecutive days with completed tasks).
     */
    private function getCurrentStreak(): int
    {
        $streak = 0;
        $currentDate = today();

        while (true) {
            $hasCompletedTask = $this->taskCompletions()
                ->where('status', TaskStatus::COMPLETED)
                ->whereDate('completed_at', $currentDate)
                ->exists();

            if (!$hasCompletedTask) {
                break;
            }

            $streak++;
            $currentDate = $currentDate->subDay();
        }

        return $streak;
    }
}
