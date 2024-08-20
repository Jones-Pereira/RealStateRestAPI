<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // `property_id`,
        // `url`,
        // `description`,
        return [
            'property_id' => Property::factory(),
            'url' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence(),
        ];
    }
}
