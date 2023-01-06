<?php

namespace App\Http\Controllers;

use App\Models\WishlistMovies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistMoviesController extends Controller
{
    public function listMovies()
    {
        $user = Auth::user();

        $WishlistMovies = WishlistMovies::where('id_user', $user['id'])->paginate(10);

        return response()->json($WishlistMovies, 200);
    }

    public function saveMovie(Request $request)
    {
        $validated = $request->validate([
            "id_movie" => "required|string",
            "title" => "required|string",
            "poster" => "required|string"
        ]);

        $user = Auth::user();

        $movieWatchedCreated = WishlistMovies::create([
            "id_user" => $user["id"],
            "id_movie" => $validated["id_movie"],
            "title" => $validated["title"],
            "poster" => $validated["poster"]
        ]);

        return response()->json($movieWatchedCreated, 200);
    }
}
