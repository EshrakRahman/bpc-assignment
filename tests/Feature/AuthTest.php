<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('renders the login page', function () {
    $this->get(route('login'))
        ->assertSuccessful()
        ->assertSee('Welcome Back');
});

it('renders the registration page', function () {
    $this->get(route('register'))
        ->assertSuccessful()
        ->assertSee('Create Account');
});

it('redirects guest users from admin to login', function () {
    $this->get(route('admin.categories'))
        ->assertRedirect(route('login'));
});

it('authenticates and redirects user with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-password'),
    ]);

    $response = $this->post(route('login'), [
        'email' => 'admin@example.com',
        'password' => 'secret-password',
    ]);

    $response->assertRedirect(route('admin.categories'));
    expect(Auth::check())->toBeTrue()
        ->and(Auth::user()->id)->toBe($user->id);
});

it('does not authenticate user with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('secret-password'),
    ]);

    $response = $this->post(route('login'), [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    expect(Auth::check())->toBeFalse();
});

it('registers a user successfully', function () {
    expect(User::count())->toBe(0);

    $response = $this->post(route('register'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ]);

    $response->assertRedirect(route('admin.categories'));
    expect(User::count())->toBe(1);
    expect(Auth::check())->toBeTrue();
});

it('allows authenticated user to logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));
    expect(Auth::check())->toBeFalse();
});
