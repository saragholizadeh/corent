<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductDetailsController extends Controller
{
    public function index(){

        $product = Product::pluck('description' , 'title');
        return response()->json($product);

    }

    public function show($id){

        $product = Product::with('plans')->find($id);
        if(is_null($product)){
            return response()->json('محصول مورد نظر یافت نشد' , 404);
        }
    }


}
