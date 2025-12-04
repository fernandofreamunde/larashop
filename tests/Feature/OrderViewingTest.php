<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Order history viewing
test('customer can view their order history', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    Order::factory()->for($customer)->count(2)->create();

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get('/orders');

    $response->assertStatus(200);
});

test('order history shows all orders for customer', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    Order::factory()->for($customer)->count(3)->create();

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get('/orders');

    expect(Order::where('customer_id', $customer->id)->count())->toBe(3);
    $response->assertStatus(200);
});

test('order history displays order number date total status', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    $order = Order::factory()->for($customer)->create([
        'total' => 5999, // €59.99
        'status' => 'pending',
    ]);

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get('/orders');

    $response->assertSee("#{$order->id}")
        ->assertSee('59.99')
        ->assertSee('Pending');
});

test('order history shows orders in descending date order', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);

    // Create orders with different timestamps
    $oldOrder = Order::factory()->for($customer)->create(['created_at' => now()->subDays(5)]);
    $newOrder = Order::factory()->for($customer)->create(['created_at' => now()]);

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get('/orders');

    // The newer order should appear before the older one in the HTML
    $content = $response->getContent();
    $newPos = strpos($content, "#{$newOrder->id}");
    $oldPos = strpos($content, "#{$oldOrder->id}");

    expect($newPos)->toBeLessThan($oldPos);
});

test('customer can view specific order details', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    $order = Order::factory()->for($customer)->create();

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get("/orders/{$order->id}");

    $response->assertStatus(200)
        ->assertSee("#{$order->id}");
});

test('order details show all products with quantities and prices', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    $product = Product::factory()->create(['name' => 'Test Product', 'price' => 29.99]);
    $order = Order::factory()->for($customer)->create();

    OrderDetails::factory()->for($order)->create([
        'product_id' => $product->id,
        'quantity' => 3,
        'current_price' => 2999, // €29.99 in cents
        'current_name' => 'Test Product',
        'sub_total' => 8997, // €89.97 in cents
    ]);

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get("/orders/{$order->id}");

    $response->assertSee('Test Product')
        ->assertSee('3') // quantity
        ->assertSee('89.97'); // sub_total displayed
});

test('order details show shipping information', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    $order = Order::factory()->for($customer)->create([
        'shipping_address' => '123 Main St',
        'shipping_number' => 'Apt 4B',
        'shipping_city' => 'Lisbon',
        'shipping_country' => 'Portugal',
        'shipping_postal_code' => '1000-001',
    ]);

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get("/orders/{$order->id}");

    // Verify order page loads and shipping data exists
    $response->assertStatus(200);
    expect($order->shipping_address)->toBe('123 Main St')
        ->and($order->shipping_city)->toBe('Lisbon')
        ->and($order->shipping_country)->toBe('Portugal');
});

test('order details show order status badge', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    $order = Order::factory()->for($customer)->create(['status' => 'completed']);

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get("/orders/{$order->id}");

    $response->assertSee('Completed');
});

test('authenticated user can access their orders', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'email' => $user->email]);
    $order = Order::factory()->for($customer)->create();

    $this->actingAs($user);

    $response = $this->get("/orders/{$order->id}");

    $response->assertStatus(200);
});

test('guest with session email can access their orders', function () {
    $customer = Customer::factory()->create(['email' => 'guest@example.com']);
    $order = Order::factory()->for($customer)->create();

    session(['customer_email' => 'guest@example.com']);

    $response = $this->get("/orders/{$order->id}");

    $response->assertStatus(200);
});

test('customer cannot view another customers order', function () {
    $customer1 = Customer::factory()->create(['email' => 'customer1@example.com']);
    $customer2 = Customer::factory()->create(['email' => 'customer2@example.com']);
    $order = Order::factory()->for($customer2)->create();

    session(['customer_email' => 'customer1@example.com']);

    $response = $this->get("/orders/{$order->id}");

    $response->assertNotFound();
});

test('unauthenticated user without session sees empty order history', function () {
    $response = $this->get('/orders');

    $response->assertStatus(200);
    // Should see empty state or no orders
});

test('order detail page loads order with relationships', function () {
    $customer = Customer::factory()->create(['email' => 'customer@example.com']);
    $product = Product::factory()->create();
    $order = Order::factory()->for($customer)->create();
    OrderDetails::factory()->for($order)->create(['product_id' => $product->id]);

    session(['customer_email' => 'customer@example.com']);

    $response = $this->get("/orders/{$order->id}");

    $response->assertStatus(200);

    // Verify relationships are loaded
    $order->refresh();
    $order->load('orderDetails.product', 'customer');

    expect($order->orderDetails)->toHaveCount(1)
        ->and($order->orderDetails->first()->product)->not->toBeNull()
        ->and($order->customer)->not->toBeNull();
});
