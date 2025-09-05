<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category'
    ];

    public $timestamps = false;
}
