<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call NurseSeeder first
        $this->call(NurseSeeder::class);

        // Seed avatars before creating patients
        $this->call(AvatarSeeder::class);

        // Seed items from predefined list
        $this->call(ItemSeeder::class);

        // Seed task templates from predefined list
        $this->call(TaskTemplateSeeder::class);

        // Create sample patients with task subscriptions
        $this->call(PatientSeeder::class);
    }
}
