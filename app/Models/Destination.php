<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Itinerary;

class Destination extends Model
{
   
    protected $fillable = ['itinerary_id', 'name', 'description'];

    //relation itinerary
    public function itinerary(){
        return $this->belongsTo(Itinerary::class);
    }
}
