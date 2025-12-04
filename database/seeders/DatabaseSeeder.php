<?php

namespace Database\Seeders;

use App\Models\Product;
// use App\Models\User;
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

        // User::factory()->create([
        //     'first_name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'isShopkeeper' => true,
        // ]);

        Product::create([
            'name' => "Woman's t-shirt",
            'description' => implode("\n", [
                'Fancy white t-shirt for girls that love the summer.',
                '',
                'Made from 100% organic cotton, this comfortable t-shirt features a relaxed fit perfect for warm weather. The breathable fabric keeps you cool throughout the day.',
                '',
                'Available in classic white with a subtle texture. Pairs perfectly with jeans, shorts, or skirts for a versatile summer wardrobe staple.',
                '',
                'Machine washable. Pre-shrunk for the perfect fit. Ethically sourced and sustainably produced.',
            ]),
            'image_url' => '/assets/t1g.jpg',
            'price' => 35.99,
        ]);

        Product::create([
            'name' => "Men's t-shirt",
            'description' => implode("\n", [
                'Nice black t-shirt for guys that enjoy to exercise.',
                '',
                'Performance athletic t-shirt designed for active lifestyles. Moisture-wicking fabric keeps you dry during intense workouts. The four-way stretch material moves with you.',
                '',
                'Features a modern athletic fit that looks great both in the gym and casual settings. Flatlock seams prevent chafing during movement.',
                '',
                'Quick-drying and odor-resistant. Available in sleek black. Perfect for running, training, or everyday wear.',
            ]),
            'image_url' => '/assets/t1.jpg',
            'price' => 35.99,
        ]);

        Product::create([
            'name' => 'Unisex Cap',
            'description' => implode("\n", [
                'For that sunny day in the Netherlands summer.',
                '',
                'Classic baseball cap with adjustable strap for the perfect fit. Curved brim provides excellent sun protection for your face and eyes.',
                '',
                'Constructed with durable cotton twill fabric. Breathable design with metal eyelets for ventilation. One size fits most with adjustable back closure.',
                '',
                'Timeless style that complements any casual outfit. Easy care - hand wash and air dry to maintain shape.',
            ]),
            'image_url' => '/assets/cap.jpg',
            'price' => 45.55,
        ]);
    }
}
