<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Camisa Polo PP', 'Camisa Polo P', 'Camisa Polo M', 'Camisa Polo G', 'Camisa Polo GG']),
            'stock_quantity' => rand(1, 99),
            'unity_price' => (rand(100, 999) / 10),
        ];
    }
}
