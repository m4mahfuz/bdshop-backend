<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\{ getJson, postJson };

uses(RefreshDatabase::class);

it ('should create a session entry for user after login', function() {

    $user = User::factory()->create(['name' => 'Jone Doe']);
    
    Auth::login($user);
    $session = Session::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson(
       route('user')         
    )->assertStatus(Response::HTTP_OK);
    
    $this->assertModelExists($session);
    $this->assertDatabaseHas('sessions', [
        'user_id' => $user->id,
    ]);
    // dump($response);
});

