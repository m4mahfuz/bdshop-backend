<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('should register an user', function() {
    $type = Type::factory()->create();
    postJson(route('admin.register'), [
        'name' => 'Jone Doe',
        'phone' => '01727777777',
        'email' => 'mm@mm.com',
        'email_verified_at' => now(),
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'type_id' => $type->id,
        'remember_token' => false
    ])
    // ->assertStatus(200);
    ->assertStatus(Response::HTTP_OK);

    $this->assertDatabaseHas('admin_users', [
        'email' => 'mm@mm.com',
    ]);
}); 
