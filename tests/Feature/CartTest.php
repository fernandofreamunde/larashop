<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Adding products to cart
test('can add product to empty cart', function () {
    $product = Product::factory()->create(['price' => 2999]);

    $response = $this->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $response->assertRedirect();

    $cart = session('cart');
    expect($cart)->toHaveKey($product->id)
        ->and($cart[$product->id]['quantity'])->toBe(1)
        ->and($cart[$product->id]['name'])->toBe($product->name)
        ->and($cart[$product->id]['price'])->toBe($product->price);
});

test('can add multiple different products to cart', function () {
    $product1 = Product::factory()->create();
    $product2 = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product1->id, 'quantity' => 2]);
    $this->post('/cart', ['product_id' => $product2->id, 'quantity' => 3]);

    $cart = session('cart');
    expect($cart)->toHaveCount(2)
        ->and($cart[$product1->id]['quantity'])->toBe(2)
        ->and($cart[$product2->id]['quantity'])->toBe(3);
});

test('adding same product increments quantity', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);
    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 3]);

    $cart = session('cart');
    expect($cart[$product->id]['quantity'])->toBe(5);
});

test('cart stores correct product data', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 2999,
        'image_url' => 'https://example.com/image.jpg',
        'description' => 'Test Description',
    ]);

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 1]);

    $cart = session('cart');
    expect($cart[$product->id]['name'])->toBe('Test Product')
        ->and($cart[$product->id]['price'])->toBe(29.99)
        ->and($cart[$product->id]['image_url'])->toBe('https://example.com/image.jpg')
        ->and($cart[$product->id]['description'])->toBe('Test Description');
});

// Updating cart
test('can update cart item quantity', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);
    $this->patch("/cart/{$product->id}", ['quantity' => 5]);

    $cart = session('cart');
    expect($cart[$product->id]['quantity'])->toBe(5);
});

test('updating quantity to 0 removes item from cart', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);
    $this->patch("/cart/{$product->id}", ['quantity' => 0]);

    $cart = session('cart', []);
    expect($cart)->not->toHaveKey($product->id);
});

// Removing from cart
test('can remove item from cart with DELETE request', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);
    $this->delete("/cart/{$product->id}");

    $cart = session('cart', []);
    expect($cart)->not->toHaveKey($product->id);
});

// Validation
test('cannot add non-existent product', function () {
    $response = $this->post('/cart', [
        'product_id' => 99999,
        'quantity' => 1,
    ]);

    $response->assertSessionHasErrors('product_id');
});

test('cannot add product with negative quantity', function () {
    $product = Product::factory()->create();

    $response = $this->post('/cart', [
        'product_id' => $product->id,
        'quantity' => -1,
    ]);

    $response->assertSessionHasErrors('quantity');
});

test('cannot add product with quantity over 99', function () {
    $product = Product::factory()->create();

    $response = $this->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 100,
    ]);

    $response->assertSessionHasErrors('quantity');
});

test('cannot add product without quantity', function () {
    $product = Product::factory()->create();

    $response = $this->post('/cart', [
        'product_id' => $product->id,
    ]);

    $response->assertSessionHasErrors('quantity');
});

test('cannot add product with non-integer quantity', function () {
    $product = Product::factory()->create();

    $response = $this->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 'abc',
    ]);

    $response->assertSessionHasErrors('quantity');
});

// Session persistence
test('cart persists in session across requests', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);

    // Make another request
    $response = $this->get('/');

    $cart = session('cart');
    expect($cart)->toHaveKey($product->id)
        ->and($cart[$product->id]['quantity'])->toBe(2);
});

// Cart calculations
test('cart total calculates correctly with multiple items', function () {
    $product1 = Product::factory()->create(['price' => 2999]); // €29.99
    $product2 = Product::factory()->create(['price' => 1499]); // €14.99

    $this->post('/cart', ['product_id' => $product1->id, 'quantity' => 2]);
    $this->post('/cart', ['product_id' => $product2->id, 'quantity' => 3]);

    $cart = session('cart');
    $total = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));

    expect($total)->toBe(104.95); // (29.99 * 2) + (14.99 * 3)
});

// Edge cases
test('empty cart shows as empty array', function () {
    $cart = session('cart', []);

    expect($cart)->toBeArray()
        ->and($cart)->toBeEmpty();
});

test('removing last item empties cart', function () {
    $product = Product::factory()->create();

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 1]);
    $this->delete("/cart/{$product->id}");

    $cart = session('cart', []);
    expect($cart)->toBeEmpty();
});

test('cart handles price changes', function () {
    $product = Product::factory()->create(['price' => 2999]);

    $this->post('/cart', ['product_id' => $product->id, 'quantity' => 1]);

    // Simulate price change
    $product->update(['price' => 3999]);

    $cart = session('cart');
    // Cart should still have old price (uses current price at time of adding)
    expect($cart[$product->id]['price'])->toBe(29.99);
});

test('maximum quantity validation works at 99', function () {
    $product = Product::factory()->create();

    $response = $this->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 99,
    ]);

    $response->assertSessionDoesntHaveErrors();

    $cart = session('cart');
    expect($cart[$product->id]['quantity'])->toBe(99);
});
