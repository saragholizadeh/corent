<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Category as CategoryResources;

use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){

        $category = new CategoryCollection (Category::whereNull('parent_id')->orderBy('created_at' , 'desc')->get());

        return response()->json([
            'category'=>$category
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request){

        $validatedData = $request->all();
        $category = new CategoryResources (Category::create($validatedData));

        return response()->json([
        "message" => "با موفقیت ثبت گردید",
            'category'=>$category,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id){

        $category = Category::find($id);
        if(is_null($category)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }
        $category = new CategoryResources (Category::find($id));

        return response()->json([
            'category'=>$category
        ] , 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, $id){

        $category_faild = Category::find($id);
        if(is_null($category_faild)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }
        $validatedData = $request->all();
        $category =  new CategoryResources (Category::find($id));
        $category->title = $validatedData['title'];
        $category->description = $validatedData['description'];
        $category->save();

        return response()->json([
            "message" => "با موفقیت ویرایش گردید",
            'category'=>$category,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $category = Category::findOrfail($id);
        if(is_null($category)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }
         $category->delete();

         return response()->json([
            "message" => "دسته بندی مورد نظر با موفقیت حذف شد",
            "category" => $category
            ],200);
    }
}
