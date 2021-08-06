<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Post;
use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;


class GetTagController extends Controller
{
    public function show($id){

        $tag = Tag::find($id);
        $tagName = $tag->name ;

        $post = new PostCollection (Post::withAllTags($tagName)->get());

        return response()->json([$post]);
    }
}
