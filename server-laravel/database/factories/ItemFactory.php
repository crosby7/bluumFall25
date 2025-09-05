<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(2, true), // e.g. "Iron Sword"
            'description' => $this->faker->sentence(8),
            'price'       => 100,
            'image'       => $this->faker->imageUrl(200, 200, 'game', true, 'item'),
            'category'    => $this->faker->randomElement([
                'Hat',
                'Eyewear',
                'Shirt',
                'Footwear',
                'Neckwear',
                'Wallpaper',
                'Carpet',
                'Window',
                'Room Item'
            ]),
        ];
    }
}
