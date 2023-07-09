<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\Utilities\AdminRole;
use Tests\Setup\UserRole;
use App\Models\Category;
use App\Models\Product;
use function Pest\Laravel\{getJson, deleteJson};

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});


it('should delete a category', function () {
   $category = Category::factory()->create();

   actingAs($this->adminUser)->
       deleteJson(route('categories.destroy', ['category' => $category->slug])
        )->assertStatus(Response::HTTP_NO_CONTENT);

   $this->assertDatabaseCount('categories', 0);
});


it('should set category_id of products to null on delete category', function() {

    $category = Category::factory()
        ->has(Product::factory()->count(3))
        ->create();

    actingAs($this->adminUser)->
        deleteJson(
            route('categories.destroy', ['category' => $category->slug])
        )->assertStatus(Response::HTTP_NO_CONTENT);
             
    
    expect(Category::count())->toBe(0);
    expect(Product::count())->toBe(3);

    Product::all()
        ->each(function(Product $product) {
            expect($product)->category_id->toBeNull();
        });        
});
