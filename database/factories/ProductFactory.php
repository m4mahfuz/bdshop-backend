<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{    
    public function definition()
    {
        $name = $this->faker->unique()->words(2, true);
        
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(2),        
            'price' => $this->faker->numberBetween($min = 300, $max = 5000),
            'unit' => $this->faker->randomElement($array = ['ml','gm','kg', 'pcs']),
            'unit_quantity' => $this->faker->numberBetween($min = 1, $max = 500),      
            'inventory_id' => fn () => Inventory::factory()->create()->id,
            'discount_id' => fn () => Discount::factory()->create()->id,
            // 'category_id' => fn () => Category::factory()->create()->id,
            // 'sku' => $this->faker->regexify('[A-Z0-9]{8}'),    
        ];
    }
}
