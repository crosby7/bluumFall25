<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'pairing_code' => \App\Models\Patient::generatePairingCode(),
            'remember_token' => Str::random(10),
            'avatar_id' => 1,
            'experience' => 0,
            'gems' => 0,
        ];
    }
}
