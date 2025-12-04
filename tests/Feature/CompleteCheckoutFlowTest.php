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

// Complete checkout flows
test('guest user can complete full checkout flow', function () {
    // Browse and add to cart
    $product = Product::factory()->create(['price' => 29.99, 'name' => 'Test Product']);

    $this->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    // View cart in session
    $cart = session('cart');
    expect($cart)->toHaveKey($product->id)
        ->and($cart[$product->id]['quantity'])->toBe(2);

    // Checkout
    $response = $this->get('/checkout');
    $response->assertStatus(200);

    // Place order
    $response = $this->post('/orders', [
        'email' => 'guest@example.com',
        'first-name' => 'John',
        'last-name' => 'Doe',
        'address' => '123 Main St',
        'city' => 'Lisbon',
        'country' => 'Portugal',
        'postal-code' => '1000-001',
    ]);

    // Verify order created
    $order = Order::first();
    expect($order)->not->toBeNull();

    // View confirmation
    $response->assertRedirect(route('orders.show', $order->id));
    $response->assertSessionHas('success', 'Order placed successfully!');
});

test('authenticated user complete checkout with pre-filled form', function () {
    // Login
    $user = User::factory()->create();
    $customer = Customer::factory()->create([
        'user_id' => $user->id,
        'email' => $user->email,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
    ]);

    $this->actingAs($user);

    // Browse and add to cart
    $product = Product::factory()->create(['price' => 49.99]);

    $this->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    // Checkout (form should be pre-filled)
    $response = $this->get('/checkout');
    $response->assertSee($user->email)
        ->assertSee('Jane')
        ->assertSee('Smith');

    // Place order
    $response = $this->post('/orders', [
        'email' => $user->email,
        'first-name' => 'Jane',
        'last-name' => 'Smith',
        'address' => '456 Oak Ave',
        'city' => 'Porto',
        'country' => 'Portugal',
        'postal-code' => '4000-001',
    ]);

    // Verify order and redirect
    expect(Order::count())->toBe(1);
    $order = Order::first();
    $response->assertRedirect(route('orders.show', $order->id));

    // View order history
    $response = $this->get('/orders');
    $response->assertSee("#{$order->id}");
});

test('multiple products in cart checkout correctly', function () {
    $product1 = Product::factory()->create(['price' => 29.99, 'name' => 'Product 1']);
    $product2 = Product::factory()->create(['price' => 19.99, 'name' => 'Product 2']);
    $product3 = Product::factory()->create(['price' => 39.99, 'name' => 'Product 3']);

    // Add multiple products to cart
    $this->post('/cart', ['product_id' => $product1->id, 'quantity' => 2]);
    $this->post('/cart', ['product_id' => $product2->id, 'quantity' => 1]);
    $this->post('/cart', ['product_id' => $product3->id, 'quantity' => 3]);

    // Place order
    $response = $this->post('/orders', [
        'email' => 'multi@example.com',
        'first-name' => 'Multi',
        'last-name' => 'Cart',
        'address' => '789 Elm St',
        'city' => 'Braga',
        'country' => 'Portugal',
        'postal-code' => '4700-001',
    ]);

    $order = Order::first();
    expect($order->orderDetails)->toHaveCount(3);

    // Verify all products are in order
    $detailNames = $order->orderDetails->pluck('current_name')->toArray();
    expect($detailNames)->toContain('Product 1')
        ->and($detailNames)->toContain('Product 2')
        ->and($detailNames)->toContain('Product 3');
});

test('cart total matches order total', function () {
    $product1 = Product::factory()->create(['price' => 25.00]);
    $product2 = Product::factory()->create(['price' => 15.00]);

    $this->post('/cart', ['product_id' => $product1->id, 'quantity' => 2]); // 50.00
    $this->post('/cart', ['product_id' => $product2->id, 'quantity' => 3]); // 45.00

    $cart = session('cart');
    $cartTotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));

    $this->post('/orders', [
        'email' => 'total@example.com',
        'first-name' => 'Total',
        'last-name' => 'Test',
        'address' => '321 Pine St',
        'city' => 'Faro',
        'country' => 'Portugal',
        'postal-code' => '8000-001',
    ]);

    $order = Order::first();
    $orderTotal = $order->total / 100; // Convert from cents

    expect(round($cartTotal, 2))->toBe(round($orderTotal, 2));
});

test('order confirmation shows correct items and prices', function () {
    $product = Product::factory()->create(['price' => 35.99, 'name' => 'Confirmation Product']);

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);

    $this->post('/orders', [
        'email' => 'confirm@example.com',
        'first-name' => 'Confirm',
        'last-name' => 'User',
        'address' => '654 Maple Dr',
        'city' => 'Coimbra',
        'country' => 'Portugal',
        'postal-code' => '3000-001',
    ]);

    $order = Order::first();
    $response = $this->get("/orders/{$order->id}");

    $response->assertSee('Confirmation Product')
        ->assertSee('2'); // quantity

    $orderDetail = $order->orderDetails->first();
    expect($orderDetail->quantity)->toBe(2)
        ->and($orderDetail->current_name)->toBe('Confirmation Product');
});
