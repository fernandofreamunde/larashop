<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('price accessor converts cents to dollars correctly', function () {
    $product = new Product;
    $product->setRawAttributes(['price' => 2999]); // Set raw cents value

    expect($product->price)->toBe(29.99);
});

test('price mutator converts dollars to cents for storage', function () {
    $product = new Product;
    $product->price = 29.99;

    expect($product->getAttributes()['price'])->toBe(2999);
});

test('price mutator handles integer dollars', function () {
    $product = new Product;
    $product->price = 30;

    expect($product->getAttributes()['price'])->toBe(3000);
});

test('formatted price returns correct format', function () {
    $product = new Product;
    $product->setRawAttributes(['price' => 2999]); // 29.99 in cents

    expect($product->formatted_price)->toBe('29.99');
});

test('formatted price handles zero', function () {
    $product = new Product;
    $product->setRawAttributes(['price' => 0]); // 0 cents

    expect($product->formatted_price)->toBe('0.00');
});

test('formatted price handles large amounts', function () {
    $product = new Product;
    $product->setRawAttributes(['price' => 123456789]); // 1234567.89 in cents

    expect($product->formatted_price)->toBe('1,234,567.89'); // Correctly formats with thousand separators
});

test('factory creates valid product', function () {
    $product = Product::factory()->create();

    expect($product)
        ->toBeInstanceOf(Product::class)
        ->and($product->name)
        ->not->toBeEmpty()
        ->and($product->description)
        ->not->toBeEmpty()
        ->and($product->image_url)
        ->not->toBeEmpty()
        ->and($product->price)
        ->toBeGreaterThan(0);
});
