<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Category;
use App\Models\Post;
use App\Http\Resources\AnalysisCollection;

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

    public function subNews($id){
        $subCategory = Category::where('id' , $id)->first();
        $subId = $subCategory->id;
        $subTitle = $subCategory->title;

        $posts = Post::where('category_id' , $subId)
            ->orderBy('created_at', 'desc')
            ->select('title', 'body')
            ->take(4)
            ->get();
        return response()->json([
            'sub_title'=>$subTitle,
            'sub_news'=>$posts
        ], 200);
    }
    public function favAnalyses(){
        $analyses =new AnalysisCollection(
            Analysis::orderBy('likes', 'desc')
            ->take(4)
            ->get()
        );

        return response()->json($analyses);
    }
}
