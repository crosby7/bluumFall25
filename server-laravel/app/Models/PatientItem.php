<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientItem extends Model
{
    use HasFactory;

    protected $table = 'patient_item';

    protected $fillable = [
        'patient_id',
        'item_id',
        'equipped',
    ];

    protected $casts = [
        'equipped' => 'boolean',
    ];

    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }

}
