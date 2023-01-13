<?php

use App\Http\Controllers\MoviesWatchedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistMoviesController;
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
    Route::get("/watched_list/list", [MoviesWatchedController::class, "listMovies"]);
    Route::post("/watched_list/save", [MoviesWatchedController::class, "saveMovie"]);
    Route::post("/watched_list/delete", [MoviesWatchedController::class, "deleteMovie"]);

    Route::get("/search/{title}", [SearchController::class, "search"]);

    Route::get("/movie_data/{movie}", [SearchController::class, "searchMovieId"]);

    Route::post("/wishlist_movie/save", [WishlistMoviesController::class, "saveMovie"]);
    Route::get("/wishlist_movie/list", [WishlistMoviesController::class, "listMovies"]);
    Route::post("/wishlist_movie/delete", [WishlistMoviesController::class, "deleteMovie"]);
});


Route::post("/login", [UserController::class, "login"]);
Route::post("/register", [UserController::class, "register"]);
