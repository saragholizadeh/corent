<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function dislikeable(){

        return $this->morphTo();
    }
}
