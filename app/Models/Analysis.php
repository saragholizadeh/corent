<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Dislike;
use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;

class Analysis extends Model implements Viewable
{
    use HasFactory;
    use \Conner\Tagging\Taggable;
    use InteractsWithViews;

    public $timestamps = false;

    protected $fillable = [
        'title' ,
        'summary' ,
        'description',
        'exchange'  ,
        'timeframe',
        'pair' ,
        'tags',
        'user_id',
        'likes',
        'dislikes',
    ];

    protected $casts=[
        'created_at'=>'timestamp',
        'updated_at'=>'timestamp',
    ];



    public function users(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->morphMany(Comment::class , 'commentable');
    }

    public function images(){
        return $this->morphMany(Image::class , 'imageable' );
    }

    public function likes(){
        return $this->morphMany(Like::class , 'likeable');
    }

    public function dislikes(){
        return $this->morphMany(Dislike::class , 'dislikeable');
    }

}
