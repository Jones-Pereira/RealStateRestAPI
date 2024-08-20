<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1000, 1000000),
            'address' => $this->faker->address,
            'city_id' => City::factory(),
            'zip_code' => $this->faker->postcode,
            'type' => $this->faker->randomElement(['sale', 'rent']),
            'status' => $this->faker->randomElement(['available', 'sold', 'rented']),
        ];
    }
}
