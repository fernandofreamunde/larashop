<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
});

// Logout
test('authenticated user can logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->delete('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('logout invalidates session', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->assertAuthenticated();

    $this->delete('/logout');

    $this->assertGuest();
});

test('logout regenerates token', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $oldSessionId = session()->getId();

    $this->delete('/logout');

    $newSessionId = session()->getId();

    expect($newSessionId)->not->toBe($oldSessionId);
});

test('redirects to homepage after logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->delete('/logout');

    $response->assertRedirect('/');
});

test('user cannot access protected resources after logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->delete('/logout');

    $this->assertGuest();
});
