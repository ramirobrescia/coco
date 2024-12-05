<?php

namespace Database\Factories;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppModelsPurchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {        
        return [
            'user_id' => User::factory(),
            'provider_id' => Provider::factory(),
            'deadline' => fake()->date('Y-m-d', '2024-12-31'),
            'price_list' => null
        ];
    }
}
