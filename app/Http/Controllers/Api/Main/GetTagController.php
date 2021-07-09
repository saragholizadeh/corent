<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Post;
use Illuminate\Http\Request;
use Conner\Tagging\Model\Tag;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use Illuminate\Database\Eloquent\Builder;


class GetTagController extends Controller
{
    public function show($id){

        $tag = Tag::find($id);
        $tagName = $tag->name ;

        $post = new PostCollection (Post::withAllTags($tagName)->get());

        return response()->json([$post]);
    }
}
