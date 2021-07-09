<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryDetailsController extends Controller
{
    public function show($id){

        //get subcategory data
        $subcategory = Category::with('posts')->where('title',)->find($id);

        return response()->json([
            'subcategoy'=>$subcategory,
        ] , 200);

    }
}
