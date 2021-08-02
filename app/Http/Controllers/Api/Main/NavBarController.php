<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Regulation;
use Illuminate\Http\Request;

class NavBarController extends Controller
{
    public  function index(){
        $categories = Category::whereNull('parent_id')->pluck('title');

        return response()->json([
            'categories'=>$categories,
        ] , 200);
    }

    public  function showSubCategories($id){

        $subCategories = Category::where('parent_id' , $id)->pluck('title');
        return response()->json([
          'subcategories'=>  $subCategories
        ] , 200);
    }
}
