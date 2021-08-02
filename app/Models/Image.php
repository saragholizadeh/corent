<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'image' , 'path'
    ];

    protected $casts=[
        'created_at'=>'timestamp'
    ];

    public function imageable(){

        return $this->morphTo();
    }

}
