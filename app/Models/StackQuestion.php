<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StackQuestion extends Model implements Viewable
{
    use HasFactory;
    use InteractsWithViews;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'body' ,
        'category_id',
        'user_id',
        'likes',
        'dislikes',
     ];

     protected $casts=[
         'created_at'=>'timestamp',
         'updated_at'=>'timestamp'
     ];


    public function category(){
        return $this->belongsTo(StackCategory::class , 'category_id');
    }

    public function comments(){
        return $this->morphMany(StackComment::class, 'commentable' );
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function images(){
        return $this->morphMany(Image::class , 'imageable');
    }

    public function tags(){
        return $this->morphToMany(StackTag::class, 'stack_taggable');
    }

    public function likes(){
        return $this->morphMany(StackLike::class, 'likeable');
    }

    public function dislikes(){
        return $this->morphMany(StackDislike::class, 'dislikeable');
    }


    public function Answers(){
        return $this->hasMany(StackAnswer::class );
    }

}
