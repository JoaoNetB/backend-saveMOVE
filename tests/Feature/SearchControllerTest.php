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

    public function test_returns_all_data_from_a_movie()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        Sanctum::actingAs($user);

        Http::preventStrayRequests();

        Http::fake([
            "http://www.omdbapi.com/?i=tt2342235&apikey=".env("OMDB_API_KEY") => Http::response([
                "Title" => "Titulo teste",
	            "Year" => "2022",
	            "Rated" => "Teste",
	            "Released" => "21 Nov 2022",
                "Runtime" => "87 min",
	            "Genre" => "Drama, Family",
	            "Director" => "Teste diretor",
	            "Writer" => "Teste escritor",
	            "Actors" => "Teste ator",
	            "Plot" => "Teste Teste",
	            "Language" => "English",
	            "Country" => "United States",
	            "Awards" => "1 nomination",
	            "Poster" => "http://imagem.com",
	            "Ratings" => [
                    "Source" => "Internet Movie Database",
			        "Value" => "6.1\/10"
	            ],
	            "Metascore" => "N\/A",
	            "imdbRating" => "6.1",
	            "imdbVotes" => "647",
	            "imdbID" => "tt2342235",
	            "Type" => "movie",
	            "DVD" => "22 Nov 2022",
	            "BoxOffice" => "N\/A",
	            "Production" => "N\/A",
	            "Website" => "N\/A",
	            "Response" => "True"
            ], 200)
        ]);

        $response = $this->getJson("/api/movie-data/tt2342235");

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
        $json->where("Title", "Titulo teste")
            ->where("Year", "2022")
            ->where("Rated", "Teste")
            ->where("Released", "21 Nov 2022")
            ->where("Runtime", "87 min")
            ->where("Genre", "Drama, Family")
            ->where("Director", "Teste diretor")
            ->where("Response", "True")
            ->etc()
        );
    }

    public function test_returns_a_message_movie_not_found()
    {
        $user = User::factory()->create([
            "email" => "test@test.com"
        ]);

        Sanctum::actingAs($user);

        Http::preventStrayRequests();

        Http::fake([
            "http://www.omdbapi.com/?i=erro&apikey=".env("OMDB_API_KEY") => Http::response([
                "Response" => "False",
                "Error" => "Incorrect IMDb ID."
            ], 200)
        ]);

        $response = $this->getJson("/api/movie-data/erro");

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
        $json->where("Response", "False")
            ->where("Error", "Incorrect IMDb ID.")
        );
    }
}
