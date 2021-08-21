<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StackAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer',
        'user_id',
        'question_id',
        'likes',
        'dislikes',
        'status'
    ];

    public $timestamps = false;

    protected $casts=[
        'created_at'=>'timestamp'
    ];

    public function question(){
        return $this->belongsTo(StackQuestion::class , 'question_id');
    }

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function comments(){
        return $this->morphMany(StackComment::class, 'commentable' );
    }

}
