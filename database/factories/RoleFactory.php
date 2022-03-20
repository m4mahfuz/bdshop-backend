<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement($array = ['super_admin','admin','operator']),
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
        ];
    }
}
