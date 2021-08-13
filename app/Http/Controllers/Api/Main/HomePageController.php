<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Resources\Json\ResourceCollection;


class HomePageController extends Controller
{
    public  function lastNews(){
        $categoryNews = Category::where('title' , 'اخبار')->first();
        $categoryNewsId = $categoryNews->id;


        $subCategories_id = Category::where('parent_id',$categoryNewsId)->get('id');

        $posts = Post::whereIn('category_id' , $subCategories_id )
            ->orderBy('created_at', 'desc')
            ->select('title', 'body')
            ->take(4)
            ->get();

        return response()->json([
            'latest_news'=>$posts
            ], 200);
    }

    public function iranNews(){
        $iranCategory = Category::where('title' , 'اخبار ایران')->first();
        $iranId = $iranCategory->id;

        $posts = Post::where('category_id' , $iranId)->
    }
}
