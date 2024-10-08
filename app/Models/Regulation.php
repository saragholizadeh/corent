<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Regulation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'country' ,
        'short_description' ,
        'description',
        'population',
        'area',
        'internet_penetration',
        'national_currency',
        'goverment',
        'president',
        'capital',
        'language',
        'economic_growth',
        'dgtl_curr_lgs',
        'dgtl_curr_tax',
        'dgtl_curr_pymt',
        'dgtl_curr_ntiol',
        'ICO',
        'crpto_antimon_rules',
    ];


    protected $casts=[
        'created_at'=>'timestamp'
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function image(){
        return $this->morphOne(Image::class , 'imageable' );
    }


}
