<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
});

// Basic order creation
test('guest can create order with valid cart and shipping details', function () {
    $product = Product::factory()->create(['price' => 29.99]);

    session(['cart' => [
        $product->id => [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 2,
            'image_url' => $product->image_url,
            'description' => $product->description,
        ],
    ]]);

    $response = $this->post('/orders', [
        'email' => 'guest@example.com',
        'first-name' => 'John',
        'last-name' => 'Doe',
        'company' => 'Test Company',
        'address' => '123 Main St',
        'apt-number' => 'Apt 4B',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertRedirect();
    expect(Order::count())->toBe(1);
});

test('authenticated user can create order', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id, 'email' => $user->email]);
    $product = Product::factory()->create(['price' => 29.99]);

    $this->actingAs($user);

    session(['cart' => [
        $product->id => [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image_url' => $product->image_url,
            'description' => $product->description,
        ],
    ]]);

    $response = $this->post('/orders', [
        'email' => $user->email,
        'first-name' => 'Jane',
        'last-name' => 'Smith',
        'address' => '456 Oak Ave',
        'city' => 'Porto',
        'country' => 'Portugal',
        'postal-code' => '4000-001',
    ]);

    $response->assertRedirect();
    expect(Order::count())->toBe(1);
});

// Customer creation/update
test('order creation creates new customer if email doesnt exist', function () {
    $product = Product::factory()->create(['price' => 29.99]);

    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'newcustomer@example.com',
        'first-name' => 'New',
        'last-name' => 'Customer',
        'address' => '789 Elm St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-002',
    ]);

    $customer = Customer::where('email', 'newcustomer@example.com')->first();
    expect($customer)->not->toBeNull()
        ->and($customer->first_name)->toBe('New')
        ->and($customer->last_name)->toBe('Customer');
});

test('order creation updates existing customer if email exists', function () {
    $customer = Customer::factory()->create([
        'email' => 'existing@example.com',
        'first_name' => 'Old',
        'last_name' => 'Name',
    ]);

    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'existing@example.com',
        'first-name' => 'Updated',
        'last-name' => 'Name',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $customer->refresh();
    expect($customer->first_name)->toBe('Updated')
        ->and(Customer::where('email', 'existing@example.com')->count())->toBe(1);
});

// Order data
test('order stores correct customer_id reference', function () {
    $customer = Customer::factory()->create(['email' => 'test@example.com']);
    $product = Product::factory()->create(['price' => 29.99]);

    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $order = Order::first();
    expect($order->customer_id)->toBe($customer->id);
});

test('order stores correct total in cents', function () {
    $product = Product::factory()->create(['price' => 29.99]);

    session(['cart' => [$product->id => ['name' => $product->name, 'price' => 29.99, 'quantity' => 2, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $order = Order::first();
    expect($order->total)->toBe(5998); // 29.99 * 2 * 100
});

test('order stores all shipping details', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main Street',
        'apt-number' => 'Apt 4B',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $order = Order::first();
    expect($order->shipping_address)->toBe('123 Main Street')
        ->and($order->shipping_number)->toBe('Apt 4B')
        ->and($order->shipping_city)->toBe('Lisbon')
        ->and($order->shipping_country)->toBe('Portugal')
        ->and($order->shipping_postal_code)->toBe('1000-001');
});

// OrderDetails creation
test('order creates OrderDetails for each cart item', function () {
    $product1 = Product::factory()->create(['price' => 29.99]);
    $product2 = Product::factory()->create(['price' => 1499]);

    session(['cart' => [
        $product1->id => ['name' => $product1->name, 'price' => 29.99, 'quantity' => 2, 'image_url' => $product1->image_url, 'description' => $product1->description],
        $product2->id => ['name' => $product2->name, 'price' => 14.99, 'quantity' => 3, 'image_url' => $product2->image_url, 'description' => $product2->description],
    ]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    expect(OrderDetails::count())->toBe(2);
});

test('OrderDetails store product snapshot', function () {
    $product = Product::factory()->create([
        'name' => 'Original Product',
        'price' => 2999,
        'description' => 'Original Description',
    ]);

    session(['cart' => [$product->id => ['name' => $product->name, 'price' => 29.99, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $orderDetail = OrderDetails::first();
    expect($orderDetail->current_name)->toBe('Original Product')
        ->and($orderDetail->current_price)->toBe(2999)
        ->and($orderDetail->current_description)->toBe('Original Description');
});

test('OrderDetails store correct quantity and sub_total', function () {
    $product = Product::factory()->create(['price' => 29.99]);

    session(['cart' => [$product->id => ['name' => $product->name, 'price' => 29.99, 'quantity' => 3, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $orderDetail = OrderDetails::first();
    expect($orderDetail->quantity)->toBe(3)
        ->and($orderDetail->sub_total)->toBe(8997); // 29.99 * 3 * 100
});

// Session and redirect behavior
test('cart is cleared after order creation', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    expect(session('cart'))->toBeNull();
});

test('session customer_email is set after order', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    expect(session('customer_email'))->toBe('test@example.com');
});

test('redirects to order confirmation page', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $order = Order::first();
    $response->assertRedirect(route('orders.show', $order->id));
});

test('shows success flash message', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHas('success', 'Order placed successfully!');
});

test('order status defaults to pending', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $order = Order::first();
    expect($order->status)->toBe('pending');
});

// Validation tests
test('cannot create order with empty cart', function () {
    session(['cart' => []]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Your cart is empty');
    expect(Order::count())->toBe(0);
});

test('cannot create order without email', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHasErrors('email');
});

test('cannot create order with invalid email format', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'not-an-email',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHasErrors('email');
});

test('cannot create order without first name', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHasErrors('first-name');
});

test('cannot create order without last name', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHasErrors('last-name');
});

test('cannot create order without address', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHasErrors('address');
});

test('cannot create order without city', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHasErrors('city');
});

test('cannot create order without country', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionHasErrors('country');
});

test('cannot create order without postal code', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
    ]);

    $response->assertSessionHasErrors('postal-code');
});

test('can create order without company (nullable)', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionDoesntHaveErrors();
    expect(Order::count())->toBe(1);
});

test('can create order without apt-number (nullable)', function () {
    $product = Product::factory()->create(['price' => 29.99]);
    session(['cart' => [$product->id => ['name' => $product->name, 'price' => $product->price, 'quantity' => 1, 'image_url' => $product->image_url, 'description' => $product->description]]]);

    $response = $this->post('/orders', [
        'email' => 'test@example.com',
        'first-name' => 'Test',
        'last-name' => 'User',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    $response->assertSessionDoesntHaveErrors();
    $order = Order::first();
    expect($order->shipping_number)->toBe('');
});
