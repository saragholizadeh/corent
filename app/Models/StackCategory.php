<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StackCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title' ,
   ];

   protected $hidden = [
    'created_at',
    'updated_at',
];

   public function questions(){
       return $this->hasMany(StackQuestion::class);
   }
}
