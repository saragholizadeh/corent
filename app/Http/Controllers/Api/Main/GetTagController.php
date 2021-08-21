<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Post;
use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;


class GetTagController extends Controller
{
    public function show($tag){
       $tag = Tag::where('tag' , $tag)->first();
       $posts = $tag->posts;



       return response()->json($tag);
    }
}
