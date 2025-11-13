<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'name' => 'Morning Medication',
                'description' => 'Take morning medications',
                'category' => 'Medical',
                'xp_value' => 50,
                'gem_value' => 50,
            ],
            [
                'name' => 'Grooming',
                'description' => 'Personal grooming and hygiene',
                'category' => 'Self-Care',
                'xp_value' => 25,
                'gem_value' => 25,
            ],
            [
                'name' => 'PT Therapy',
                'description' => 'Physical therapy session',
                'category' => 'Therapy',
                'xp_value' => 100,
                'gem_value' => 100,
            ],
            [
                'name' => 'Lunch',
                'description' => 'Lunch meal',
                'category' => 'Meals',
                'xp_value' => 25,
                'gem_value' => 25,
            ],
            [
                'name' => 'OT Therapy',
                'description' => 'Occupational therapy session',
                'category' => 'Therapy',
                'xp_value' => 100,
                'gem_value' => 100,
            ],
            [
                'name' => 'Rec Activity',
                'description' => 'Recreational activity',
                'category' => 'Recreation',
                'xp_value' => 25,
                'gem_value' => 25,
            ],
            [
                'name' => 'School: Reading',
                'description' => 'Reading lesson or activity',
                'category' => 'Education',
                'xp_value' => 50,
                'gem_value' => 50,
            ],
            [
                'name' => 'School: Math',
                'description' => 'Math lesson or activity',
                'category' => 'Education',
                'xp_value' => 100,
                'gem_value' => 100,
            ],
            [
                'name' => 'Bandage Change',
                'description' => 'Medical bandage change',
                'category' => 'Medical',
                'xp_value' => 100,
                'gem_value' => 100,
            ],
            [
                'name' => 'Night Medications',
                'description' => 'Take evening medications',
                'category' => 'Medical',
                'xp_value' => 50,
                'gem_value' => 50,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
