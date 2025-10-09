<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Patient extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'pairing_code',
        'paired_at',
        'device_identifier',
        'avatar_id',
        'experience',
        'gems',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'pairing_code',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'paired_at' => 'datetime',
        ];
    }

    public function inventory()
    {
        return $this->hasMany(\App\Models\PatientItem::class);
    }

    public function taskSubscriptions()
    {
        return $this->hasMany(TaskSubscription::class);
    }

    public function taskCompletions()
    {
        return $this->hasManyThrough(TaskCompletion::class, TaskSubscription::class, 'patient_id', 'subscription_id');
    }

    /**
     * Generate a unique 6-digit pairing code for this patient.
     *
     * @return string
     */
    public static function generatePairingCode(): string
    {
        do {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('pairing_code', $code)->exists());

        return $code;
    }
}
