<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
   #[OA\Post(path: '/api/register', summary: 'register a unn user ')]
    #[OA\Parameter(name: 'name', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'email', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'password', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 201, description: 'User registered successfully')]
    // #[OA\Post(path: "/api/register",summary: "Register a new user",tags: ["Auth"])]
    //     #[OA\RequestBody(required: true,content: new OA\JsonContent(
    //             required: ["name","email","password"],
    //             properties: [
    //                 new OA\Property(property: "name", type: "string", example: "hanane"),
    //                 new OA\Property(property: "email", type: "string", example: "hanane@gmail.com"),
    //                 new OA\Property(property: "password", type: "string", example: "hanane123"),
    //             ]
    //         )
    //     )]
    //     #[OA\Response(response: 201, description: "User registered successfully")]


    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6' 
        ]);

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        return response()->json($user);
    }


    #[OA\Post(path: '/api/login', summary: 'connecter a un comote')]
    #[OA\Parameter(name: 'email', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'password', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 201, description: 'User connecter avec success!!')]
    //  #[OA\Post(path: "/api/login",summary: "connecter a votre compte",tags: ["Auth"])]
    //     #[OA\RequestBody(required: true,content: new OA\JsonContent(
    //             required: ["email","password"],
    //             properties: [
    //                 new OA\Property(property: "email", type: "string", example: "hanane@gmail.com"),
    //                 new OA\Property(property: "password", type: "string", example: "hanane123"),
    //             ]
    //         )
    //     )]
    //     #[OA\Response(response: 201, description: "Bien connection !")]

    public function login(Request $request){
         $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6' 
        ]);

        $user= User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'message'=> 'data invalide'
            ],401);
        }
        $token= $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token
        ]);
        
    }


    #[OA\Post(path: "/api/logout",summary: "Logout user",tags:["Auth"],security: [["sanctumAuth" => []]])]
    #[OA\Response(response: 200,description: "Logout successful")]

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}

