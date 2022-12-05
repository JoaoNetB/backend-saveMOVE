<?php

namespace Tests\Feature;

use App\Models\MoviesWatched;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            "id_move" => 12234
        ]);
        
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/watched_list');

        $response->assertStatus(200);

        $response->assertJson([
            "total" => 1
        ]);

        $response->assertJsonPath("data.0.id", 1);
    }

    public function test_movies_watched_list_unauthorized_access()
    {
        $response = $this->getJson('/api/watched_list');

        $response->assertStatus(401);

        $response->assertJson([
            "message" => "Unauthenticated."
        ]);
    }
}
