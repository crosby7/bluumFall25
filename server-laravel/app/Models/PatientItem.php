<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientItem extends Model
{
    use HasFactory;

    protected $table = 'patient_item';

    // Composite primary key
    protected $primaryKey = ['patient_id', 'item_id'];
    public $incrementing = false;

    protected $fillable = [
        'patient_id',
        'item_id',
        'equipped',
    ];

    protected $casts = [
        'equipped' => 'boolean',
    ];

    public $timestamps = false;

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the value for a given key.
     *
     * @param  string  $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }

}
