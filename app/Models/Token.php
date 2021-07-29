<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'timestamp',
        'expires_in' => 'timestamp',
        'new_code_date'=>'timestamp',
    ];


    protected $fillable = [
        'user_id',
        'code',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
