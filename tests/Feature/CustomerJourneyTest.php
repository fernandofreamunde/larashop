<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
});

// Customer journeys
test('new customer can register and complete full journey', function () {
    // Register new customer
    $response = $this->post('/register', [
        'first_name' => 'New',
        'last_name' => 'Customer',
        'email' => 'new@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    // Verify customer created
    $customer = Customer::where('email', 'new@example.com')->first();
    expect($customer)->not->toBeNull();

    // Place an order
    $product = Product::factory()->create(['price' => 29.99]);
    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 1]);

    $this->post('/orders', [
        'email' => 'new@example.com',
        'first-name' => 'New',
        'last-name' => 'Customer',
        'address' => '123 New St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    // Verify order created
    $order = Order::where('customer_id', $customer->id)->first();
    expect($order)->not->toBeNull();

    // View order history
    $response = $this->get('/orders');
    $response->assertSee("#{$order->id}");
});

test('returning customer can login and place order', function () {
    // Create existing customer
    $user = User::factory()->create(['email' => 'returning@example.com']);
    $customer = Customer::factory()->create([
        'user_id' => $user->id,
        'email' => 'returning@example.com',
        'first_name' => 'Returning',
        'last_name' => 'Customer',
    ]);

    // Add item to cart before login
    $product = Product::factory()->create(['price' => 49.99]);
    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);

    // Login
    $this->post('/login', [
        'email' => 'returning@example.com',
        'password' => 'password',
    ]);

    // Cart should still be there
    $cart = session('cart');
    expect($cart)->toHaveKey($product->id);

    // Place order
    $this->post('/orders', [
        'email' => 'returning@example.com',
        'first-name' => 'Returning',
        'last-name' => 'Customer',
        'address' => '456 Return Ave',
        'city' => 'Porto',
        'country' => 'Portugal',
        'postal-code' => '4000-001',
    ]);

    // Verify order
    expect(Order::where('customer_id', $customer->id)->count())->toBe(1);
});

test('guest checkout then register later links customer to user', function () {
    // Guest places order
    $product = Product::factory()->create(['price' => 39.99]);
    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 1]);

    $this->post('/orders', [
        'email' => 'guest-then-user@example.com',
        'first-name' => 'Guest',
        'last-name' => 'User',
        'address' => '789 Guest Ln',
        'city' => 'Faro',
        'country' => 'Portugal',
        'postal-code' => '8000-001',
    ]);

    // Verify customer created
    $customer = Customer::where('email', 'guest-then-user@example.com')->first();
    expect($customer)->not->toBeNull()
        ->and($customer->user_id)->toBeNull(); // No user yet

    // Guest registers with same email
    $this->post('/register', [
        'first_name' => 'Guest',
        'last_name' => 'User',
        'email' => 'guest-then-user@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    // Verify user created
    $user = User::where('email', 'guest-then-user@example.com')->first();
    expect($user)->not->toBeNull();

    // Customer should now be linked to user
    $customer->refresh();
    expect($customer->user_id)->toBe($user->id);
});
