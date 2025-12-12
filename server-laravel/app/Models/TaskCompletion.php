<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    use HasFactory;

    protected $table = 'task_completions';

    protected $fillable = [
        'subscription_id',
        'scheduled_for',
        'completed_at',
        'rewards_distributed_at',
        'status',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'completed_at' => 'datetime',
        'rewards_distributed_at' => 'datetime',
        'status' => TaskStatus::class,
    ];

    /**
     * Relationships
     */
    public function subscription()
    {
        return $this->belongsTo(TaskSubscription::class, 'subscription_id');
    }
}
