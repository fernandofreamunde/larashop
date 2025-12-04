<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Product browsing
test('can view product list on homepage', function () {
    Product::factory()->count(3)->create();

    $response = $this->get('/');

    $response->assertStatus(200);
});

test('homepage displays all products', function () {
    $products = Product::factory()->count(5)->create();

    $response = $this->get('/');

    $response->assertStatus(200);
    foreach ($products as $product) {
        $response->assertSee($product->name);
    }
});

test('homepage shows product names prices images', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 29.99,
        'image_url' => '/assets/test-product.jpg',
    ]);

    $response = $this->get('/');

    $response->assertSee('Test Product')
        ->assertSee($product->formatted_price)
        ->assertSee('/assets/test-product.jpg');
});

test('can view individual product details', function () {
    $product = Product::factory()->create();

    $response = $this->get("/product/{$product->id}");

    $response->assertStatus(200)
        ->assertSee($product->name);
});

test('product detail page shows full description', function () {
    $product = Product::factory()->create([
        'description' => 'This is a detailed product description with lots of information.',
    ]);

    $response = $this->get("/product/{$product->id}");

    $response->assertSee('This is a detailed product description with lots of information.');
});

test('product detail page shows correct price', function () {
    $product = Product::factory()->create(['price' => 49.99]);

    $response = $this->get("/product/{$product->id}");

    $response->assertSee($product->formatted_price);
});

test('returns 404 for non-existent product', function () {
    $response = $this->get('/product/99999');

    $response->assertNotFound();
});
