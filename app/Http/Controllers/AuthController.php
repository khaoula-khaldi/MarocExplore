<?php

namespace App\Http\Controllers;

use app\models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(){
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6' 
        ]);

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'->bcrypt($request->password)
        ]);

        $token==user->createToken('auth_token')->pleinTextToken;

    }
}
