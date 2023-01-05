<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WishlistMoviesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_save_movie_in_movies_wishlist()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/wishlist_movie/save', [
            "id_movie" => "tt000000",
            "title" => "teste",
            "poster" => "https://teste-image.jpg"
        ]);

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
        $json->where("id_user", 1)
            ->where("id_movie", "tt000000")
            ->where("title", "teste")
            ->where("poster", "https://teste-image.jpg")
            ->where("id", 1)
            ->etc()
        );
    }

    public function test_save_movie_in_movies_wishlist_without_data()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/wishlist_movie/save', [
            "id_movie" => "",
            "title" => "",
            "poster" => ""
        ]);

        $response->assertStatus(422);

        $response->assertJson(fn (AssertableJson $json) =>
        $json->where("message", "The id movie field is required. (and 2 more errors)")
            ->etc()
        );
    }
}
