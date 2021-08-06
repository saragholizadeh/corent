<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag'];
    public $timestamps = false;

    protected $casts = [
        'tag' => 'array',
        'created_at'=>'timestamp',
    ];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
