<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('should register an user', function() {
    
    postJson(route('register'), [
        'name' => 'Jone Doe',
        'phone' => '01727777777',
        'email' => 'mm@mm.com',
        'email_verified_at' => now(),
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'remember_token' => false
    ])
    ->assertStatus(Response::HTTP_OK);

    $this->assertDatabaseHas('users', [
        'email' => 'mm@mm.com',
    ]);
}); 

it ('should login a user', function() {

    $user = User::factory()->create(['name' => 'Jone Doe']);

    postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
        'remember' => false
    ])
    ->assertStatus(Response::HTTP_OK);

    expect($user)
        ->id->toBe(1)
        ->name->toBe('Jone Doe');       
});

it ('should logout a user', function() {
    postJson(route('logout'))
    ->assertStatus(Response::HTTP_OK);    
});