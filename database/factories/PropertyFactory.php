<?php

namespace Database\Factories;

use App\Models\User;
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
        $for_rent = fake()->boolean();
        return [
            "name" => fake()->unique()->numerify("house-####"),
            "price" => fake()->randomFloat(2, 1000, 10000),
            "location" => fake()->address(),
            "description" => fake()->text(),
            "bedrooms" => fake()->numberBetween(1, 4),
            "bathrooms" => fake()->numberBetween(1, 3),
            "for_rent" => $for_rent,
            "max_tenants" => $for_rent ? fake()->numberBetween(2,5) : null,
            "min_contract_time" => $for_rent ? fake()->numberBetween(2,5) : null,
            "accept_animals" => $for_rent ? fake()->boolean() : null,
            "user_id" => User::all()->random()->id
        ];
    }
}
