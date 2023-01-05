<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WishlistMovies>
 */
class WishlistMoviesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "id_movie" => fake()->numberBetween(1000, 9999),
            "id_user" => fake()->numberBetween(1, 10),
            "title" => fake()->text(50),
            "poster" => fake()->imageUrl
        ];
    }
}
