<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class DiscountFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->words(7, true),
            'description' => $this->faker->paragraph(2),
        ];
    }
}
