<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSubscription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($subscription) {
            // Automatically create an initial TaskCompletion when a subscription is created
            TaskCompletion::create([
                'subscription_id' => $subscription->id,
                'scheduled_for' => Carbon::parse($subscription->start_at)->setTimeFromTimeString($subscription->scheduled_time),
                'status' => TaskStatus::INCOMPLETE,
            ]);
        });
    }

    protected $table = 'task_subscriptions';

    protected $fillable = [
        'patient_id',
        'task_id',
        'start_at',
        'scheduled_time',
        'interval_days',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'scheduled_time' => 'datetime:H:i:s',
        'is_active' => 'boolean',
        'interval_days' => 'integer',
    ];

    /**
     * Scope to get only active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class, 'subscription_id');
    }
}
