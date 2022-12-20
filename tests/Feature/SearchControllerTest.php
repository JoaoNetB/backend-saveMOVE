<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function test_returns_the_movie_search()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);


        Sanctum::actingAs($user);

        Http::preventStrayRequests();
        
        Http::fake([
            "http://www.omdbapi.com/?s=teste-titulo&apikey=".env("OMDB_API_KEY") => Http::response([
                "Search" => [
                    "Title" => "teste title",
                    "Year" => "2022",
                    "imdbID" => "eet33234",
                    "Type" => "movie",
                    "Poster" => "https://testeimagem.com/imagem"
                ],
                "totalResults" => "1",
	            "Response" => "True"
            ], 200)
        ]);

        $response = $this->getJson("/api/search/teste-titulo");
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where("Search", [
                "Title" => "teste title",
                "Year" => "2022",
                "imdbID" => "eet33234",
                "Type" => "movie",
                "Poster" => "https://testeimagem.com/imagem"

            ])->etc()
        );
        
    }
    
    public function test_returns_the_movie_search_not_found()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);


        Sanctum::actingAs($user);

        Http::preventStrayRequests();

        Http::fake([
            "http://www.omdbapi.com/?s=asdfasdf&apikey=".env("OMDB_API_KEY") => Http::response([
                "Response" => "False",
	            "Error" => "Movie not found!"
            ], 200)
        ]);

        $response = $this->getJson("/api/search/asdfasdf");
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where("Response", "False")
                ->where("Error", "Movie not found!")
        );
    }
}
