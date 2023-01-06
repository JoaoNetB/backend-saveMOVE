<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WishlistMovies;
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

    public function test_wishlist_movies_list()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        WishlistMovies::factory()->create([
            "id_user" => 1,
            "id_movie" => 12234,
            "title" => "teste",
            "poster" => "https://teste.jpg"
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/wishlist_movie/list');

        $response->assertStatus(200);

        $response->assertJson([
            "total" => 1
        ]);

        $response->assertJsonPath("data.0.id", 1);
    }

    public function test_wishlist_movies_list_unauthorized_access()
    {
        $response = $this->getJson('/api/wishlist_movie/list');

        $response->assertStatus(401);

        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }
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
