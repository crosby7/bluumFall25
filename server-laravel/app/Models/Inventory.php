<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'user_id',
        'item_id',
        'equipped',
    ];

    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }
    
}
