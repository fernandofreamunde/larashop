<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'Test User',
            'email' => 'test@example.com',
            'isShopkeeper' => true,
        ]);

        Product::factory()->create([
            'name' => "Woman's t-shirt",
            'description' => implode("\n", [
                'Fancy white t-shirt for girls that love the summer.',
                ...fake()->paragraphs(3),
            ]),
            'image_url' => '/assets/t1g.jpg',
            'price' => 35.99,
        ]);

        Product::factory()->create([
            'name' => "Men's t-shirt",
            'description' => implode("\n", [
                'Nice black t-shirt for guys that enjoy to exercise.',
                ...fake()->paragraphs(3),
            ]),
            'image_url' => '/assets/t1.jpg',
            'price' => 35.99,
        ]);

        Product::factory()->create([
            'name' => 'Unisex Cap',
            'description' => implode("\n", [
                'For that sunny day in the Netherlands summer.',
                ...fake()->paragraphs(3),
            ]),
            'image_url' => '/assets/cap.jpg',
            'price' => 45.55,
        ]);

        Order::factory()->withItems()->count(10)->create();
    }
}
