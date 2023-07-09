<?php

namespace Tests\Feature;

use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\{ getJson, postJson };

uses(RefreshDatabase::class);

it ('should create a cart item', function() {

    $user = User::factory()->create(['name' => 'Jone Doe']);
    
    // Auth::login($user);

    // $session = Session::factory()->create(['user_id' => $user->id]);

    
    $discount = Discount::factory()->has(Deduct::factory())->create();
    $products = Product::factory()                        
                        ->count(3)                       
                        ->hasAttached(Category::factory()->count(2))
                        ->create(['discount_id' => $discount->id]);    
    
    // $discount->deduct()->create([
    //     'rate' => 5,
    //     'active' => true,
    //     'minimum_spending' => 200,
    //     'limit' => null,
    //     'starting' => now()->subDays(3),
    //     'ending' => now()->addDays(4)
    // ]);
    
    // $sessionId = Str::random(40);

    // $cart = $this->withSession(['session_id' => $sessionId])->postJson(
    $cart = postJson(
       route('carts.store', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => 2
       ])
    )->assertStatus(Response::HTTP_CREATED)
    ->json('data');
    
    // dump($cart);

    // $this->assertDatabaseHas('carts', [
    //     'session_id' => $sessionId,
    // ]);

    expect($cart)
        ->product_id->toBe($product->id);
});

