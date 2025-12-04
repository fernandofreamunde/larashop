<?php

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('has many orders relationship works', function () {
    $customer = Customer::factory()->create();
    $orders = Order::factory()->count(3)->for($customer)->create();

    expect($customer->orders)->toHaveCount(3)
        ->and($customer->orders->first())->toBeInstanceOf(Order::class);
});

test('factory creates valid customer', function () {
    $customer = Customer::factory()->create();

    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($customer->email)->not->toBeEmpty()
        ->and($customer->first_name)->not->toBeEmpty()
        ->and($customer->last_name)->not->toBeEmpty();
});

test('company field is nullable', function () {
    $customer = Customer::factory()->create(['company' => null]);

    expect($customer->company)->toBeNull();
});

test('email is unique', function () {
    $email = 'test@example.com';
    Customer::factory()->create(['email' => $email]);

    expect(fn () => Customer::factory()->create(['email' => $email]))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);
});
