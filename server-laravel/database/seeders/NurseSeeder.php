<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NurseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Nurse::create([
            'first_name' => 'Dev',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'Bluumfall2025', // Will be hashed by model
            'email_verified_at' => now(),
        ]);
    }
}
