<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{    
    public function definition()
    {
        return [
            'name' => $this->faker->words(5, true),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->numberBetween($min = 300, $max = 5000),
            'category_id' => fn () => Category::factory()->create()->id,
            'discount_id' => null//Discount::factory()
        ];
    }
}
