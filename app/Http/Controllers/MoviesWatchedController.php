<?php

namespace App\Http\Controllers;

use App\Models\MoviesWatched;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoviesWatchedController extends Controller
{
    
    public function listMovies(Request $request)
    {   
        try{
            $user = Auth::user();
            
            $MoviesWatched = MoviesWatched::where('id_user', $user['id'])->paginate(10);
        
            return response()->json($MoviesWatched, 200);

        } catch (Exception $err) {
            return response()->json($err, 500);
        }
    }
}
