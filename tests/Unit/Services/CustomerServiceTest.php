<?php

use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('returns customer by user_id when authenticated', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $service = new CustomerService;
    $result = $service->getCustomer();

    expect($result)->toBeInstanceOf(Customer::class)
        ->and($result->id)->toBe($customer->id);
});

test('returns customer by session email when not authenticated', function () {
    $customer = Customer::factory()->create(['email' => 'test@example.com']);

    session(['customer_email' => 'test@example.com']);

    $service = new CustomerService;
    $result = $service->getCustomer();

    expect($result)->toBeInstanceOf(Customer::class)
        ->and($result->id)->toBe($customer->id);
});

test('returns null when no customer found', function () {
    $service = new CustomerService;
    $result = $service->getCustomer();

    expect($result)->toBeNull();
});

test('prioritizes auth over session when both exist', function () {
    $user = User::factory()->create();
    $authCustomer = Customer::factory()->create(['user_id' => $user->id, 'email' => 'auth@example.com']);
    $sessionCustomer = Customer::factory()->create(['email' => 'session@example.com']);

    $this->actingAs($user);
    session(['customer_email' => 'session@example.com']);

    $service = new CustomerService;
    $result = $service->getCustomer();

    expect($result)->toBeInstanceOf(Customer::class)
        ->and($result->id)->toBe($authCustomer->id)
        ->and($result->email)->toBe('auth@example.com');
});

test('handles missing session gracefully', function () {
    $service = new CustomerService;
    $result = $service->getCustomer();

    expect($result)->toBeNull();
});

test('returns null when authenticated but no customer linked', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $service = new CustomerService;
    $result = $service->getCustomer();

    expect($result)->toBeNull();
});
