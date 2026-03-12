<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Itinerary;
use App\Models\User;
use App\Models\Destination;

class Itinerary extends Model
{
    protected $fillable = ['title','category','duration','image','user_id'];
    //relation destination
    // public function destinations(){
    //     return $this->hasMany(Destination::class);
    // }

    //relation avec favories 
    // public function favoris(){
    //     return $this->belongsToMany(Itinerary::class, 'favoris');
    // }

    //relation avec user 
    public function user(){
        return $this->belongsTo(User::class);
    }
}
