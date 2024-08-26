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
        return [
            "id_property" => Property::all()->random()->id,
            "original_name" => fake()->unique()->numerify("house_img###.png"),
            "hash_name" => fake()->unique()->numerify("#####.png")
        ];
    }
}
