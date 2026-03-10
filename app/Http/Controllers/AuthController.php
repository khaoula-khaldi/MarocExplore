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

        return response()->json($user);
    }

    public function login(){
         $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6' 
        ]);

        $user= User::where('email',$request->email)->first();

        if(!$user || !Has::check($request->password,$user->password)){
            return reponse()->json([
                'message'=> 'data invalide'
            ],401);
        }
        $token= $user->createToken('auth_token')->pleinTextToken;

        return reponse()->json([
            'user'=>$user,
            'token'=>$token
        ]);
        
    }
}
