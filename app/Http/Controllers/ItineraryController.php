<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itinerary;
use OpenApi\Attributes as OA;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    #[OA\Get(path: "/api/itineraries",summary: "Get all itineraries")]
    #[OA\Response(response: 200,description: "List of itineraries")]
    public function index()
    {
         return Itinerary::with('user')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(path: '/api/itineraries', summary: 'Create a new itinerary', security: [["sanctumAuth" => []]])]
    #[OA\Parameter(name: 'title', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'category', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'duration', in: 'query', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'image', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 201, description: 'Itinerary created successfully')]
    public function store(Request $request)
    {
    $request->validate(['title' => 'required|string',
                        'category' => 'required',
                        'duration' => 'required|integer']);
    $itinerary = Itinerary::create(['title' => $request->title,
                                    'category' => $request->category,
                                    'duration' => $request->duration,
                                    'image' => $request->image,
                                    'user_id' => auth()->id()]);
      return response()->json($itinerary,201);
    }

    /**
     * Display the specified resource.
     */

    #[OA\Get(path: '/api/itineraries/{id}', summary: 'Get itinerary by id')]
    #[OA\Parameter(name: 'id',in: 'path',required: true,schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Itinerary found')]
    #[OA\Response(response: 404, description: 'Itinerary not found')]
    public function show(string $id)
    {
         return Itinerary::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */

    #[OA\Put(path: '/api/itineraries/{id}', summary: 'Update itinerary', security: [["sanctumAuth" => []]])]
    #[OA\Parameter(name: 'id',in: 'path',required: true,schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'title', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'category', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'duration', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'image', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'Itinerary updated')]
    #[OA\Response(response: 403, description: 'Unauthorized')]
    public function update(Request $request, string $id)
    {
        $itinerary = Itinerary::findOrFail($id);

        if($itinerary->user_id != auth()->id()){
            return response()->json(['message'=>'Unauthorized'],403);
        }$itinerary->update($request->all());

        return response()->json($itinerary);
    }

    /**
     * Remove the specified resource from storage.
     */

    #[OA\Delete(path: '/api/itineraries/{id}', summary: 'Delete itinerary', security: [["sanctumAuth" => []]])]
    #[OA\Parameter(name: 'id',in: 'path',required: true,schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Itinerary deleted successfully')]
    #[OA\Response(response: 403, description: 'Unauthorized')]
    public function destroy(string $id)
    {
        $itinerary = Itinerary::findOrFail($id);

        if($itinerary->user_id != auth()->id()){
            return response()->json(['message'=>'Unauthorized'],403);
            }$itinerary->delete();
            
        return response()->json(['message'=>'Deleted']);
    }

    /**
     * filtrage.
     */


    #[OA\Get(path: '/api/itineraries/filter',summary: 'Filter itineraries by category and/or duration',security: [["sanctumAuth" => []]])]
    #[OA\Parameter(name: 'category',in: 'query',required: false,schema: new OA\Schema(type: 'string'),description: 'Filter by category')]
    #[OA\Parameter(name: 'duration',in: 'query',required: false,schema: new OA\Schema(type: 'integer'),description: 'Filter by duration')]
    #[OA\Response(response: 200,description: 'Filtered list of itineraries')]

    public function filter(Request $request)
    {
        $query = Itinerary::query();

        // filter category
            if($request->category){
                $query->where('category', $request->category);
            }

        // filter duration
            if($request->duration){
                $query->where('duration', $request->duration);
            }

        $itineraries = $query->get();

        return response()->json($itineraries);
    }
}





     

     
