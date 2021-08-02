<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
         'parent_id',
         'title' ,
         'description',
         'status' ,
    ];
    protected $casts=[
        'created_at'=>'timestamp'
    ];


    public function parent(){
    return $this->belongsTo(Category::class );
    }


    public function children(){
        return $this->hasMany(Category::class , 'parent_id' , 'id');
    }

    public function posts(){
        return $this->hasMany(Post::class );
    }


}
