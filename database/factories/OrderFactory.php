<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\OrderDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'total' => 0, // Will be calculated after adding order details
            'status' => fake()->randomElement([
                'pending',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
            ]),
            'shipping_address' => fake()->streetAddress(),
            'shipping_number' => fake()->buildingNumber(),
            'shipping_city' => fake()->city(),
            'shipping_country' => fake()->country(),
            'shipping_postal_code' => fake()->postcode(),
        ];
    }

    /**
     * Create an order with order details and calculate the total.
     */
    public function withItems(?int $itemCount = null): static
    {
        if ($itemCount !== null && $itemCount > 3) {
            throw new \Exception(
                'Cannot create order with more than 3 unique items',
            );
        }

        return $this->afterCreating(function ($order) use ($itemCount) {
            $availableProducts = \App\Models\Product::all();

            if ($availableProducts->isEmpty()) {
                throw new \Exception(
                    'No products available to create order items',
                );
            }

            $count = $itemCount ?? fake()->numberBetween(1, 3);
            $selectedProducts = $availableProducts->random(
                min($count, $availableProducts->count()),
            );

            $orderDetails = collect();
            foreach ($selectedProducts as $product) {
                $quantity = fake()->numberBetween(1, 5);
                $currentPrice = (int) round($product->price * 100);

                $orderDetail = OrderDetails::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'current_price' => $currentPrice,
                    'current_name' => $product->name,
                    'current_description' => $product->description,
                    'sub_total' => $currentPrice * $quantity,
                ]);

                $orderDetails->push($orderDetail);
            }

            $total = $orderDetails->sum('sub_total');
            $order->update(['total' => $total]);
        });
    }
}
