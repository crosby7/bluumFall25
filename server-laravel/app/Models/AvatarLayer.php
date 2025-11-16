<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvatarLayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'avatar_id',
        'layer_number',
        'layer_name',
        'image_path',
    ];

    protected $casts = [
        'avatar_id' => 'integer',
        'layer_number' => 'integer',
    ];

    /**
     * Get the avatar that owns this layer.
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Avatar::class);
    }
}
