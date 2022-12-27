<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isEmpty;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $response = http::get("http://www.omdbapi.com/?s={$request['title']}&apikey=".env("OMDB_API_KEY"));

        return response()->json($response->json(), $response->status());
    }

    public function searchMovieId(Request $request)
    {
        $response = http::get("http://www.omdbapi.com/?i={$request['movie']}&apikey=".env("OMDB_API_KEY"));

        return response()->json($response->json(), $response->status());
    }
}
