<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{    
    public function definition()
    {
        return [
            'sku' => $this->faker->regexify('[A-Z0-9]{8}'),    
            'quantity' => $this->faker->randomNumber($nbDigits = 2, $strict = false),
            // 'price' => $this->faker->numberBetween($min = 300, $max = 5000),     
            // 'product_id' => fn () => Product::factory()->create()->id,
            // 'discount_id' => fn () => Discount::factory()->create()->id,
        ];
    }
}
