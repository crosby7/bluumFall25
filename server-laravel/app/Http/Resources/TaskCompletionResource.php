<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCompletionResource extends JsonResource
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
            'subscription_id' => $this->subscription_id,
            'scheduled_for' => $this->scheduled_for,
            'completed_at' => $this->completed_at,
            'status' => $this->status->value,
            'task' => [
                'id' => $this->subscription->task->id,
                'name' => $this->subscription->task->name,
                'description' => $this->subscription->task->description,
                'category' => $this->subscription->task->category,
                'xp_value' => $this->subscription->task->xp_value,
                'gem_value' => $this->subscription->task->gem_value,
            ],
            'subscription' => [
                'interval_days' => $this->subscription->interval_days,
                'timezone' => $this->subscription->timezone,
                'is_active' => $this->subscription->is_active,
            ],
        ];
    }
}
