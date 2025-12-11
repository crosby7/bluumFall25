<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'description',
        'base_path',
        'layer_count',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'layer_count' => 'integer',
        'sort_order' => 'integer',
        'species' => 'string',
    ];

    /**
     * Get the layers for this avatar.
     */
    public function layers(): HasMany
    {
        return $this->hasMany(AvatarLayer::class)->orderBy('layer_number');
    }

    /**
     * Get the patients that have this avatar.
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}
