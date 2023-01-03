<?php

namespace Tests\Feature;

use App\Models\MoviesWatched;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MoviesWatchedControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_movies_watched_list()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        MoviesWatched::factory()->create([
            "id_user" => 1,
            "id_movie" => 12234
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/watched_list/list');

        $response->assertStatus(200);

        $response->assertJson([
            "total" => 1
        ]);

        $response->assertJsonPath("data.0.id", 1);
    }

    public function test_movies_watched_list_unauthorized_access()
    {
        $response = $this->getJson('/api/watched_list/list');

        $response->assertStatus(401);

        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_save_movie_watched()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/watched_list/save', [
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

    public function test_save_movie_watched_without_id()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/watched_list/save', [
            "id_movie" => ""
        ]);

        $response->assertStatus(422);

        $response->assertJson(fn (AssertableJson $json) =>
        $json->where("message", "The id movie field is required. (and 2 more errors)")
            ->etc()
        );
    }
}
