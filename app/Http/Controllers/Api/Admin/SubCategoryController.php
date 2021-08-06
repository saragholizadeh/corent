<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubCategoryRequest;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort' , 'created_at');

        $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc' : 'asc' ;

        $sortColumn = ltrim($sortColumn , '-');

        $subcategory =  Category::whereNotNull('parent_id')
        ->orderBy($sortColumn , $sortDirection)
        ->paginate(10);

        return response()->json($subcategory);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        $validatedData = $request->all();

        $subcategory = Category::create($validatedData);

        return response()->json([
            "success" => true,
            "message" => "با موفقیت ثبت گردید",
            "data" => $subcategory
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subcategory = Category::find($id);
        if (is_null($subcategory)) {
            return response()->json('دسته بندی مورد نظر یافت نشد', 404);
        }
        return response()->json($subcategory, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubCategoryRequest $request, $id)
    {
        $category_faild = Category::find($id);
        if(is_null($category_faild)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }

        $validatedData = $request->all();

        $subcategory = Category::find($id);
        $subcategory->parent_id = $validatedData['parent_id'];
        $subcategory->title = $validatedData['title'];
        $subcategory->description = $validatedData['description'];
        $subcategory->save();

        return response()->json([
        "success" => true,
        "message" => "با موفقیت ویرایش گردید",
        "data" => $subcategory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subcategory = Category::findOrfail($id);
        if(is_null($subcategory)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }
         $subcategory->delete();

         return response()->json([
            "success" => true,
            "message" => "دسته بندی مورد نظر با موفقیت حذف شد",
            "data" => $subcategory
            ]);    }
}
