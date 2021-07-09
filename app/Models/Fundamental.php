<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Dislike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fundamental extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $casts = [
        'type'=>'array',
    ];


    protected $fillable = [
        'currency',
        'event_date',
        'description',
        'type',
        'user_id',
        'likes',
        'dislikes',
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }


   public function likes(){
       return $this->morphMany(Like::class, 'likeable');
   }

   public function dislikes(){
        return $this->morphMany(Dislike::class, 'dislikeable');
    }

}

