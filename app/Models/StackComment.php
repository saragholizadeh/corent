<?php

namespace App\Models;

use App\Models\StackLike;
use App\Models\StackDislike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StackComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'title',
        'user_id',
        'likes',
        'dislikes',

     ];

     protected $hidden = [
        'updated_at',
     ];


    public function commentable(){
        return $this->morphTo();
    }

    public function replies(){
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function likes(){
        return $this->morphMany(StackLike::class, 'likeable');
    }

    public function dislikes(){
        return $this->morphMany(StackDislike::class, 'dislikeable');
    }

}
