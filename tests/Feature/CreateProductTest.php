<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Deduct;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\Setup\UserRole;
use Tests\Utilities\AdminRole;
use function Pest\Laravel\{ getJson, postJson };

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});

it('should show all products', function() { 

    $discount = Discount::factory()->has(Deduct::factory())->create();
    
    $products = Product::factory()                        
                        ->count(3)                       
                        ->hasAttached(Category::factory()->count(2))
                        ->create(['discount_id' => $discount->id]);    
    $response = getJson(route('products.index'))
        ->assertStatus(Response::HTTP_OK)
        ->json('data');

    $this->assertDatabaseCount('products', 3);

    // dump($response);
});


it('should create a new product with inventory', function() {

    $category1 = Category::factory()->create(['name' => 'Fruits']);
    $category2 = Category::factory()->create(['name' => 'Vegetables']);
    
    $discount = Discount::factory()->has(Deduct::factory())->create();
    
    $product = actingAs($this->adminUser)->postJson(
        route('products.store'), [
            'name' => 'Pine Apple',
            'slug' => 'Pine Apple',
            'description' => 'Fresh pine apple directly from garden.',
            'price' => 500,
            'quantity' => 50,
            'sku' => 'AK123546',
            'discount_id' => $discount->id,
            'category_ids' => [$category1->id, $category2->id],
        ]
    )
    ->assertStatus(Response::HTTP_CREATED)
    ->json('data');    
    
    // dump($product);

    $this->assertDatabaseCount('inventories', 1);

    $inventory = Inventory::first();
    
    expect($product)
        ->name->toBe('Pine Apple')
        ->description->toBe('Fresh pine apple directly from garden.')
        ->price->toBe(500);

    expect($inventory)
        ->sku->toBe('AK123546')
        ->quantity->toBe(50);
});

