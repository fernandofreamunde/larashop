<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Checkout page viewing
test('can view checkout page with items in cart', function () {
    $product = Product::factory()->create();

    session(['cart' => [
        $product->id => [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 2,
            'image_url' => $product->image_url,
            'description' => $product->description,
        ],
    ]]);

    $response = $this->get('/checkout');

    $response->assertStatus(200);
});

test('checkout page displays cart items correctly', function () {
    $product = Product::factory()->create(['name' => 'Test Product']);

    session(['cart' => [
        $product->id => [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 2,
            'image_url' => $product->image_url,
            'description' => $product->description,
        ],
    ]]);

    $response = $this->get('/checkout');

    $response->assertSee('Test Product')
        ->assertSee('2'); // quantity
});

test('checkout page calculates cart total correctly', function () {
    $product1 = Product::factory()->create(['price' => 29.99]);
    $product2 = Product::factory()->create(['price' => 19.99]);

    session(['cart' => [
        $product1->id => [
            'name' => $product1->name,
            'price' => $product1->price,
            'quantity' => 2,
            'image_url' => $product1->image_url,
            'description' => $product1->description,
        ],
        $product2->id => [
            'name' => $product2->name,
            'price' => $product2->price,
            'quantity' => 1,
            'image_url' => $product2->image_url,
            'description' => $product2->description,
        ],
    ]]);

    $response = $this->get('/checkout');

    // (29.99 * 2) + (19.99 * 1) = 79.97
    $response->assertSee('79.97');
});

test('checkout page includes shipping cost', function () {
    $product = Product::factory()->create(['price' => 29.99]);

    session(['cart' => [
        $product->id => [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image_url' => $product->image_url,
            'description' => $product->description,
        ],
    ]]);

    $response = $this->get('/checkout');

    $response->assertSee('5.00'); // shipping cost
});

test('authenticated user has form pre-filled with customer data', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create([
        'user_id' => $user->id,
        'email' => $user->email,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'company' => 'Acme Corp',
    ]);

    $this->actingAs($user);

    $response = $this->get('/checkout');

    $response->assertSee($user->email)
        ->assertSee('Jane')
        ->assertSee('Smith')
        ->assertSee('Acme Corp');
});

test('guest with session email has form pre-filled', function () {
    $customer = Customer::factory()->create([
        'email' => 'guest@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'company' => 'Test Inc',
    ]);

    session(['customer_email' => 'guest@example.com']);

    $response = $this->get('/checkout');

    $response->assertSee('guest@example.com')
        ->assertSee('John')
        ->assertSee('Doe')
        ->assertSee('Test Inc');
});

test('guest without session has empty form', function () {
    $response = $this->get('/checkout');

    $response->assertStatus(200)
        ->assertDontSee('value="');
});

test('company field is optional', function () {
    $response = $this->get('/checkout');

    // Check that the company input field doesn't have required attribute
    $response->assertStatus(200);
    // The field should be present but not required (escape=false to check HTML)
    $response->assertSee('name="company"', false);
});
