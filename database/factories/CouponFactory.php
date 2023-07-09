<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'amount_type' => Coupon::AMOUNT_TYPE_PERCENTAGE, // TYPE_FIXED            
            'code' => 'test10',
            'categories' => [8,9,10,11],
            'usage' => '2',
            'minimum_spending' => 300,
        ];
    }
}
