<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Post extends Model implements Viewable
{
    use HasFactory;
    use \Conner\Tagging\Taggable;
    use InteractsWithViews;

    protected $hidden = [

        'deleted_at',
        'updated_at',
    ];

    protected $fillable = [
        'category_id' ,
        'title' ,
        'body' ,
        'study_time',
        'status',
        'tags',
        'user_id',

    ];



    public function category(){
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable' );
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function images(){
        return $this->morphMany(Image::class , 'imageable' );
    }


    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }

    public function dislikes(){
        return $this->morphMany(Dislike::class, 'dislikeable');
    }
}

