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
// use function Pest\Laravel\postJson;

uses(
    RefreshDatabase::class, 
    AdminRole::class
);


beforeEach(function () {
    $this->adminUser = UserRole::setAs('super_admin')->create();       
});

it('should ignore the name attribute for unique validation rule', function() {

    $category = Category::factory()
        ->has(Product::factory()->count(3))
        ->create(['name' => 'Fruits']);

    actingAs($this->adminUser)->
        putJson(
            route('categories.update', ['category' => $category->id]), 
            [
                'name' => 'Fruits',
                'description' => 'New description for Fruits category'
            ]
        )->assertStatus(Response::HTTP_OK);
    
    $category = getJson(
        route('categories.show', ['category' => $category->id]) 
    )->json('data');
    
    expect($category)
        ->name->toBe('Fruits')
        ->description->toBe('New description for Fruits category')
        ->productCount->toBe(3);
});


it('should update a category', function() {

    $category = Category::factory()
        ->has(Product::factory()->count(3))
        ->create();

    // dump($category);

    actingAs($this->adminUser)->
        putJson(
            route('categories.update', ['category' => $category->id]), 
            [
                'name' => 'Fruits',
                'description' => 'New description for Fruits category'
            ]
        )->assertStatus(Response::HTTP_OK);
    
    $category = getJson(
        route('categories.show', ['category' => $category->id]) 
    )->json('data');

    // dump($category);

    expect($category)
        ->name->toBe('Fruits')
        ->description->toBe('New description for Fruits category')
        ->productCount->toBe(3);
});