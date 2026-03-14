<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favoris';
    protected $fillable = ['user_id', 'itinerary_id'];
    public $timestamps = true;

    //relation avec iteneraire 
    public function itinerary(){
        return $this->belongsTo(Itinerary::class);
    }
}
