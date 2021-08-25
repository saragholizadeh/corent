<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Tag;
use App\Http\Resources\TagPost as TagResources;
use App\Http\Controllers\Controller;

class GetTagController extends Controller
{
    public function show($tag){
        $findTag = Tag::find($tag);
        if(!$findTag){
            return response()->json('tag not found ' , 404);
        }
       $tag = new TagResources(Tag::where('tag' , $tag)->first());
       return response()->json($tag ,200);
    }
}
