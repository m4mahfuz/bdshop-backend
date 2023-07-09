<?php

namespace Tests\Feature;

// use App\Models\Category;
// use App\Models\Deduct;
// use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Tests\Setup\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\Utilities\AdminRole;
use function Pest\Laravel\{ getJson, postJson };

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});


it('shoud update a inventory', function() {
    
    $inventory = Inventory::factory()
                        ->has(Product::factory())
                        ->create();

    $inventory = actingAs($this->adminUser)->putJson(
        route('inventories.update', ['inventory' => $inventory->id]), [            
            'sku' => 'ABC2345210',
            'quantity' => 50,
        ]
    )
    ->assertStatus(Response::HTTP_OK)
    ->json('data');    
});