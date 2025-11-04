<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Shirts
            ['name' => 'Blue Hoodie', 'description' => null, 'price' => 300, 'image' => 'hoodieBlue', 'category' => 'Shirt'],
            ['name' => 'Bone Hoodie', 'description' => null, 'price' => 450, 'image' => 'hoodieBone', 'category' => 'Shirt'],
            ['name' => 'Color Block Hoodie', 'description' => null, 'price' => 300, 'image' => 'hoodieColorBlock', 'category' => 'Shirt'],
            ['name' => 'Green Hoodie', 'description' => null, 'price' => 300, 'image' => 'hoodieGreen', 'category' => 'Shirt'],
            ['name' => 'Red Hoodie', 'description' => null, 'price' => 300, 'image' => 'hoodieRed', 'category' => 'Shirt'],
            ['name' => 'Splatter Hoodie', 'description' => null, 'price' => 500, 'image' => 'hoodieSplatter', 'category' => 'Shirt'],
            ['name' => 'Black Shirt', 'description' => null, 'price' => 300, 'image' => 'shirtBlack', 'category' => 'Shirt'],
            ['name' => 'Blue Shirt', 'description' => null, 'price' => 300, 'image' => 'shirtBlue', 'category' => 'Shirt'],
            ['name' => 'Green Shirt', 'description' => null, 'price' => 300, 'image' => 'shirtGreen', 'category' => 'Shirt'],
            ['name' => 'Red Shirt', 'description' => null, 'price' => 300, 'image' => 'shirtRed', 'category' => 'Shirt'],
            ['name' => 'Riley Shirt', 'description' => null, 'price' => 200, 'image' => 'shirtRiley', 'category' => 'Shirt'],
            ['name' => 'Yellow Shirt', 'description' => null, 'price' => 200, 'image' => 'shirtYellow', 'category' => 'Shirt'],

            // Footwear
            ['name' => 'Black Gators', 'description' => null, 'price' => 300, 'image' => 'gatorBlack', 'category' => 'Footwear'],
            ['name' => 'Blue Gators', 'description' => null, 'price' => 300, 'image' => 'gatorBlue', 'category' => 'Footwear'],
            ['name' => 'Green Gators', 'description' => null, 'price' => 300, 'image' => 'gatorGreen', 'category' => 'Footwear'],
            ['name' => 'Purple Gators', 'description' => null, 'price' => 300, 'image' => 'gatorPurple', 'category' => 'Footwear'],
            ['name' => 'Red Gators', 'description' => null, 'price' => 300, 'image' => 'gatorRed', 'category' => 'Footwear'],
            ['name' => 'Splatter Gators', 'description' => null, 'price' => 500, 'image' => 'gatorSplatter', 'category' => 'Footwear'],
            ['name' => 'Yellow Gators', 'description' => null, 'price' => 300, 'image' => 'gatorYellow', 'category' => 'Footwear'],

            // Eyewear
            ['name' => 'Black Glasses', 'description' => null, 'price' => 200, 'image' => 'glassesBlack', 'category' => 'Eyewear'],
            ['name' => 'Blue Glasses', 'description' => null, 'price' => 200, 'image' => 'glassesBlue', 'category' => 'Eyewear'],
            ['name' => 'Purple Glasses', 'description' => null, 'price' => 200, 'image' => 'glassesPurple', 'category' => 'Eyewear'],
            ['name' => 'Red Glasses', 'description' => null, 'price' => 200, 'image' => 'glassesRed', 'category' => 'Eyewear'],
            ['name' => 'Shades', 'description' => null, 'price' => 300, 'image' => 'glassesShades', 'category' => 'Eyewear'],
            ['name' => 'White Glasses', 'description' => null, 'price' => 200, 'image' => 'glassesWhite', 'category' => 'Eyewear'],

            // Hats
            ['name' => 'Black Hat', 'description' => null, 'price' => 300, 'image' => 'hatBlack', 'category' => 'Hat'],
            ['name' => 'Blue Hat', 'description' => null, 'price' => 300, 'image' => 'hatBlue', 'category' => 'Hat'],
            ['name' => 'Green Hat', 'description' => null, 'price' => 300, 'image' => 'hatGreen', 'category' => 'Hat'],
            ['name' => 'Purple Hat', 'description' => null, 'price' => 300, 'image' => 'hatPurple', 'category' => 'Hat'],
            ['name' => 'Splatter Hat', 'description' => null, 'price' => 500, 'image' => 'hatSplatter', 'category' => 'Hat'],
        ];

        foreach ($items as $item) {
            // Programmatically construct the full image path
            $imageName = $item['image'];
            $category = $item['category'];
            $item['image'] = "assets/items/{$category}/{$imageName}.png";

            Item::create($item);
        }
    }
}
