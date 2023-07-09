<?php

namespace Database\Factories;

use App\Models\Deduct;
use Illuminate\Database\Eloquent\Factories\Factory;


class DeductFactory extends Factory
{
    public function definition()
    {
        return [                        
            'amount' => $this->faker->randomDigitNotNull(),
            'active' => true,
            // 'minimum_spending' => 500,
            'limit' => null,
            'starting' => now()->subDays(3),
            'ending' => now()->addDays(4)
        ];
    }
}
