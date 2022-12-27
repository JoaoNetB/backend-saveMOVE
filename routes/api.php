<?php

use App\Http\Controllers\MoviesWatchedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware("auth:sanctum")->group(function() {
    Route::get("/watched_list", [MoviesWatchedController::class, "listMovies"]);

    Route::get("/search/{title}", [SearchController::class, "search"]);

    Route::get("/movie-data/{movie}", [SearchController::class, "searchMovieId"]);
});


Route::post("/login", [UserController::class, "login"]);
