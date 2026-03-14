<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use OpenApi\Attributes as OA;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(path: '/api/favorites',summary: 'Get user favorites',security: [["sanctumAuth" => []]])]
    #[OA\Response(response: 200,description: 'List of user favorites')]
    public function index()
    {
        
        $favorites = Favorite::where('user_id', auth()->id())
                             ->with('itinerary')
                             ->get();

        return response()->json($favorites);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(path: '/api/itineraries/{id}/favorite',summary: 'Add an itinerary to favorites',security: [["sanctumAuth" => []]])]
    #[OA\Parameter(name: 'id',in: 'path',required: true,schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 201,description: 'Added to favorites')]
    #[OA\Response(response: 400,description: 'Already in favorites')]
    public function store($id)
    {
        //duplicate
        $exists = Favorite::where('user_id', auth()->id())
                          ->where('itinerary_id', $id)
                          ->first();

        if($exists){
            return response()->json([
                'message' => 'Already in favorites'
            ], 400);
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'itinerary_id' => $id
        ]);

        return response()->json([
            'message' => "Added to favorites"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */

    #[OA\Delete(path: '/api/itineraries/{id}/favorite',summary: 'Remove an itinerary from favorites',security: [["sanctumAuth" => []]])]
    #[OA\Parameter(name: 'id',in: 'path',required: true,schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200,description: 'Removed from favorites')]
    public function destroy($id)
    {
        Favorite::where('user_id', auth()->id())
                ->where('itinerary_id', $id)
                ->delete();

        return response()->json([
            'message' => 'Removed from favorites'
        ]);
    }
}