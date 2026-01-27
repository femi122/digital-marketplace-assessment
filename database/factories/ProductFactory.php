<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // If we don't specify a user, create one automatically
            'user_id' => \App\Models\User::factory(),
            
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100, 10000), // Random price between $1.00 and $100.00
            'file_path' => 'products/dummy.pdf', // A fake path for testing
        ];
    }
}
