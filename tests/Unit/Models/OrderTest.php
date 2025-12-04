<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('belongs to customer relationship works', function () {
    $customer = Customer::factory()->create();
    $order = Order::factory()->for($customer)->create();

    expect($order->customer)->toBeInstanceOf(Customer::class)
        ->and($order->customer->id)->toBe($customer->id);
});

test('has many order details relationship works', function () {
    $order = Order::factory()->create();
    $orderDetails = OrderDetails::factory()->count(3)->for($order)->create();

    expect($order->orderDetails)->toHaveCount(3)
        ->and($order->orderDetails->first())->toBeInstanceOf(OrderDetails::class);
});

test('factory creates valid order', function () {
    $order = Order::factory()->create();

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->customer_id)->toBeGreaterThan(0)
        ->and($order->total)->toBeGreaterThanOrEqual(0)
        ->and($order->status)->not->toBeEmpty()
        ->and($order->shipping_address)->not->toBeEmpty()
        ->and($order->shipping_city)->not->toBeEmpty()
        ->and($order->shipping_country)->not->toBeEmpty()
        ->and($order->shipping_postal_code)->not->toBeEmpty();
});

test('factory withItems creates order with details', function () {
    Product::factory()->count(5)->create();

    $order = Order::factory()->withItems(3)->create();

    expect($order->orderDetails)->toHaveCount(3)
        ->and($order->orderDetails->first()->product_id)->toBeGreaterThan(0);
});

test('status values are valid', function ($status) {
    $order = Order::factory()->create(['status' => $status]);

    expect($order->status)->toBe($status);
})->with(['pending', 'processing', 'completed', 'cancelled']);
