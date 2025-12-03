<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetails>
 */
class OrderDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product =
            Product::inRandomOrder()->first() ?? Product::factory()->create();
        $quantity = fake()->numberBetween(1, 5);
        $currentPrice = (int) round($product->price * 100);

        return [
            "order_id" => Order::factory(),
            "product_id" => $product->id,
            "quantity" => $quantity,
            "current_price" => $currentPrice,
            "current_name" => $product->name,
            "current_description" => $product->description,
            "sub_total" => $currentPrice * $quantity,
        ];
    }
}
