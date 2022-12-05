<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    
}
