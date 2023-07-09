<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\Utilities\AdminRole;
use Tests\Setup\UserRole;
use App\Models\Inventory;
use App\Models\Product;
use function Pest\Laravel\{getJson, deleteJson};

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});

it('should delete a product', function () {
    
    $inventory = Inventory::factory()->create();

    $product = Product::factory()->for($inventory)->create();

    actingAs($this->adminUser)->
    deleteJson(route('products.destroy', compact('product')))
        ->assertStatus(Response::HTTP_NO_CONTENT);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});


