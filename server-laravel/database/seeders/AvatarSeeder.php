<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Avatar;
use App\Models\AvatarLayer;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $avatars = [
            [
                'name' => 'Axolotl',
                'species' => 'axolotl',
                'description' => 'A cute axolotl avatar',
                'base_path' => 'assets/Avatar/Axolotl',
                'layer_count' => 5,
                'is_default' => true,
                'sort_order' => 1,
                'layers' => ['FirstLayer', 'SecondLayer', 'ThirdLayer', 'FourthLayer', 'FifthLayer'],
            ],
            [
                'name' => 'Dog',
                'species' => 'dog',
                'description' => 'A cheerful dog avatar',
                'base_path' => 'assets/Avatar/Dog',
                'layer_count' => 5,
                'is_default' => false,
                'sort_order' => 2,
                'layers' => ['FirstLayer', 'SecondLayer', 'ThirdLayer', 'FourthLayer', 'FifthLayer'],
            ],
            [
                'name' => 'Cat',
                'species' => 'cat',
                'description' => 'An adorable cat avatar',
                'base_path' => 'assets/Avatar/Cat',
                'layer_count' => 5,
                'is_default' => false,
                'sort_order' => 3,
                'layers' => ['FirstLayer', 'SecondLayer', 'ThirdLayer', 'FourthLayer', 'FifthLayer'],
            ],
        ];

        foreach ($avatars as $avatarData) {
            // Extract layers array before creating avatar
            $layers = $avatarData['layers'];
            unset($avatarData['layers']);

            // Create the avatar
            $avatar = Avatar::create($avatarData);

            // Create all layers for this avatar
            foreach ($layers as $index => $layerName) {
                $layerNumber = $index + 1;
                AvatarLayer::create([
                    'avatar_id' => $avatar->id,
                    'layer_number' => $layerNumber,
                    'layer_name' => $layerName,
                    'image_path' => "{$avatar->base_path}/{$avatar->name}_{$layerName}.png",
                ]);
            }
        }
    }
}
