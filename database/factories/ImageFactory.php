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
    $imageName = fake()->image(storage_path("app/public/images/"), width: 250, height: 250, fullPath: False);
        return [
            "property_id" => Property::all()->random()->id,
            "original_name" => fake()->unique()->numerify("house_img###.png"),
            "link" => "storage/images/{$imageName}"
        ];
    }
}
