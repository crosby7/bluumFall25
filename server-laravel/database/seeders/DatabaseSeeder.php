<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\Task;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'dev',
            'email' => 'dev@example.com',
        ]);
        User::factory()->create([
            'name' => 'Jared Carthalion',
            'email' => 'jared@example.com',
        ]);
        User::factory()->create([
            'name' => 'Jace Beleren',
            'email' => 'jace@example.com',
        ]);
        User::factory()->create([
            'name' => 'Lilliana Voss',
            'email' => 'zombiez@example.com',
        ]);                       
        //User::factory(10)->create();

        Item::factory(50)->create();
        Inventory::factory(10)->create();
        Task::factory(5)->create();
    }
}
