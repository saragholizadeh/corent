<?php

namespace App\Models;

use App\Models\StackLike;
use App\Models\StackDislike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StackComment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'comment',
        'user_id',
        'likes',
        'dislikes',
     ];

    protected $casts=[
        'created_at'=>'timestamp'
    ];

    public function commentable(){
        return $this->morphTo();
    }

    public function likes(){
        return $this->morphMany(StackLike::class, 'likeable');
    }

    public function dislikes(){
        return $this->morphMany(StackDislike::class, 'dislikeable');
    }

}
