<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Post;
use App\Models\Category;
use App\Http\Resources\Category as CategoryResources;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;

class CategoryDetailsController extends Controller
{
    public function show( $id){

        $category = new CategoryResources(Category::find($id));

        if(is_null($category)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }

        $subCategories_id = Category::where('parent_id',$id)->get('id');

       $posts = new PostCollection(Post::whereIn('category_id' , $subCategories_id )->orderBy('created_at', 'desc')->get());

        return response()->json([
            'category'=>$category ,
            'posts'=>$posts,
            ] , 200);
    }
}
