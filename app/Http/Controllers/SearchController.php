<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    
    public function search(Request $request)
    {       
        $response = Http::get("http://www.omdbapi.com/?s={$request['title']}&apikey=".env("OMDB_API_KEY"))
            ->throw()->json();

        if($response['Response'] == "False") {
            return response()->json($response, 404);
        }

        return response()->json($response, 200);
    }
}
