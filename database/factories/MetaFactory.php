<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meta>
 */
class MetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(4, true),
            'description' => $this->faker->paragraph(),
            'keywords' =>$this->faker->randomElements($array = array ('aaa','bbbbb','cccccc', 'ddd', 'eeeee'), $count = 2)
        ];
    }
}
