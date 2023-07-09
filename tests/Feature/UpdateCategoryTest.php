<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\Setup\UserRole;
use Tests\Utilities\AdminRole;
use function Pest\Laravel\{getJson, putJson};

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();
});


it('should ignore the name and slug attributes for unique validation rule', function() {

    $category = Category::factory()
        ->has(Product::factory()->count(3))
        ->create();

    $updatedCategory = actingAs($this->adminUser)->putJson(
        route('categories.update', ['category' => $category->slug]), [
            'name' => $category->name, 
            'slug' => $category->slug, 
            'description' => 'New description for Fruits category'
        ]
    )->assertStatus(Response::HTTP_OK);
    
    // dump($updatedCategory);

    $categoryResponse = getJson(
        route('categories.show', [ 'category' => $category->slug ]) 
    )->json('data');
    
    expect($categoryResponse)
        ->name->toBe($category->name)
        ->slug->toBe($category->slug)
        ->description->toBe('New description for Fruits category')
        ->productCount->toBe(3);
});


it('should update a category', function() {

    $category = Category::factory()
        ->has(Product::factory()->count(3))
        ->create();

    $updatedCategory = actingAs($this->adminUser)->putJson(
            route('categories.update', ['category' => $category->slug]), [
                'name' => 'Fruits',
                'slug' => 'Fruits', 
                'description' => 'New description for Fruits category'
            ]
        )
        ->assertStatus(Response::HTTP_OK)
        ->json('data');
   
    // dump($updatedCategory);
   
    $category = getJson(
        route('categories.show', [ 'category' => $updatedCategory['slug']] ) 
    )->json('data');

    expect($category)
        ->name->toBe('Fruits')
        ->description->toBe('New description for Fruits category')
        ->productCount->toBe(3);
});