<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Termwind\Components\Raw;

class UserController extends Controller
{

    public function login(Request $request)
    {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            $token = $user->createToken("token_access");
            return response()->json($token, 200);
        }

        return response()->json('Usuário inválido', 401);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8"
        ]);

        $user = User::create([
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "password" => Hash::make($validatedData["password"])
        ]);

        return response()->json($user, 200);
    }
}
