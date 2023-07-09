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
use function Pest\Laravel\{ getJson, putJson };

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});


it('shoud update a product by admins only', function() {

    $discount = Discount::factory()->has(Deduct::factory())->create();
    $category = Category::factory()->create();

    $product = Product::factory()
                        ->hasAttached($category)
                        ->create(['discount_id' => $discount->id]);    
    
    $inventory = $product->inventory;
    
    $price = $product->price;

    $product = actingAs($this->adminUser)->putJson(
        route('products.update', ['product' => $product->slug]), [
            'name' => 'Green Pine Apple',
            'slug' => 'Green Pine Apple',
            'description' => 'New Fresh pine apple directly from garden 2022.',
            'price' => $price,
            'sku' => $inventory->sku,
            'quantity' => $inventory->quantity,
            'category_ids' => [$category->id],
        ]
    )
    ->assertStatus(Response::HTTP_OK)
    ->json('data');    

    expect($product)
        ->name->toBe('Green Pine Apple')
        ->description->toBe('New Fresh pine apple directly from garden 2022.')
        ->price->toBe($price);
});

