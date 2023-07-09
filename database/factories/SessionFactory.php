<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => Str::random(40),                                
            'user_id' => fn () => User::factory()->create()->id,
            'total' => $this->faker->numberBetween($min = 1000, $max = 9000),
            'ip_address' => $this->faker->ipv4
        ];
    }
}
