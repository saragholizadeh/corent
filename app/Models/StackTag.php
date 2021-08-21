<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StackTag extends Model
{
    use HasFactory;
    protected $fillable = ['tag'];
    public $timestamps = false;

    protected $casts = [
        'created_at'=>'timestamp',
    ];

    public function questions()
    {
        return $this->morphedByMany(StackQuestion::class, 'stack_taggable');
    }
}
