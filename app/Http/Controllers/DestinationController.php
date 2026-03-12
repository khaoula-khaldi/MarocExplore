<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($itinerary_id)
    {
        return Destination::where("itinerary_id",$itinerary_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
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
