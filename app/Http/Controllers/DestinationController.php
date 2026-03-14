<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use OpenApi\Attributes as OA;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    #[OA\Get(path: '/api/itineraries/{itinerary_id}/destinations',summary: 'Get destinations of an itinerary')]
    #[OA\Parameter(name: 'itinerary_id',in: 'path',required: true,schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'List of destinations')]
    public function index($itinerary_id)
    {
        return Destination::where("itinerary_id",$itinerary_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */

    #[OA\Post(path: '/api/destinations', summary: 'Create a new destination', security: [["sanctumAuth" => []]])]
    #[OA\Parameter(name: 'itinerary_id', in: 'query', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'name', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'description', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 201, description: 'Destination created successfully')]
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255',
                            'description' => 'nullable|string',
                            'itinerary_id' => 'required|exists:itineraries,id']);
        
        $destination = Destination::create(['itinerary_id' => $request->itinerary_id,
                                    'name' => $request->name,
                                    'description' => $request->description]);
    
        return response()->json([
                                    'message' => 'Destination created successfully',
                                    'destination' => $destination
                                ], 201); 
    }



    
}
