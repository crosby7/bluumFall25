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
            'last_name' => 'Nurse',
            'email' => 'nurse@example.com',
            'password' => 'password', // Will be hashed by model
        ]);
    }
}
