<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type>
 */
class TypeFactory extends Factory
{
    
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(1, true)
        ];
    }
}
