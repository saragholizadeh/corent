<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Urequest extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable  = [
        'experience',
        'user_id',
        'interested',
        'specialty',
        'bio'
    ];

    public function User(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
