<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'task_subscriptions';

    protected $fillable = [
        'patient_id',
        'task_id',
        'start_at',
        'interval_days',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
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
