<?php

namespace App\Http\Controllers;

use App\Models\MoviesWatched;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MoviesWatchedController extends Controller
{

    public function listMovies()
    {
        try{
            $user = Auth::user();

            $MoviesWatched = MoviesWatched::where('id_user', $user['id'])->paginate(10);

            return response()->json($MoviesWatched, 200);

        } catch (Exception $err) {
            return response()->json($err, 500);
        }
    }

    public function saveMove(Request $request)
    {
        $validated = $request->validate([
            "id_movie" => "required|string"
        ]);

        $user = Auth::user();

        $movieWatchedCreated = MoviesWatched::create([
            "id_user" => $user['id'],
            "id_movie" => $validated["id_movie"]
        ]);

        return response()->json($movieWatchedCreated, 200);
    }
}
