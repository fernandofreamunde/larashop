<?php

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('belongs to order relationship works', function () {
    $order = Order::factory()->create();
    $orderDetail = OrderDetails::factory()->for($order)->create();

    expect($orderDetail->order)->toBeInstanceOf(Order::class)
        ->and($orderDetail->order->id)->toBe($order->id);
});

test('belongs to product relationship works', function () {
    $product = Product::factory()->create();
    $orderDetail = OrderDetails::factory()->create(['product_id' => $product->id]);

    expect($orderDetail->product)->toBeInstanceOf(Product::class)
        ->and($orderDetail->product->id)->toBe($product->id);
});

test('stores product snapshot correctly', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 2999,
        'description' => 'Test Description',
    ]);

    $orderDetail = OrderDetails::factory()->create([
        'product_id' => $product->id,
        'current_name' => $product->name,
        'current_price' => 2999,
        'current_description' => $product->description,
    ]);

    expect($orderDetail->current_name)->toBe('Test Product')
        ->and($orderDetail->current_price)->toBe(2999)
        ->and($orderDetail->current_description)->toBe('Test Description');
});

test('sub total is calculated correctly', function () {
    $orderDetail = OrderDetails::factory()->create([
        'quantity' => 3,
        'current_price' => 2999,
        'sub_total' => 8997,
    ]);

    expect($orderDetail->sub_total)->toBe(8997)
        ->and($orderDetail->sub_total)->toBe($orderDetail->quantity * $orderDetail->current_price);
});
