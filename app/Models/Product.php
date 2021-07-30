<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $fillable = [
        'title',
        'description',
   ];
    protected $casts=[
        'created_at'=>'timestamp'
    ];


    public function plans(){
       return $this->hasMany(Plan::class);
   }

   public function image(){
    return $this->morphOne(Image::class , 'imageable' );
}

}
