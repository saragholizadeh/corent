<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Dislike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
       'name',
       'email' ,
       'comment',
       'status' ,
       'images',
       'user_id',
       'likes',
       'dislikes',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
        'image',
        'commentable_type',
        "commentable_id",
    ];

    protected $casts=[
        'created_at'=>'timestamp'
    ];


    public function commentable(){
       return $this->morphTo();

   }

   public function replies(){
       return $this->hasMany(Comment::class, 'parent_id');
   }

   public function likes(){
       return $this->morphMany(Like::class, 'likeable');
   }

   public function dislikes(){
       return $this->morphMany(Dislike::class, 'dislikeable');
   }

}
