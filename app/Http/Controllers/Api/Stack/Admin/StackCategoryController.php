<?php

namespace App\Http\Controllers\Api\Stack\Admin;

use Illuminate\Http\Request;
use App\Models\StackCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStackCategoryRequest;
use Illuminate\Support\Facades\Validator;

class StackCategoryController extends Controller
{
    public function index(){
        $category = StackCategory::all();
        return response()->json($category);
    }

    public function store(StoreStackCategoryRequest $request){

        $validateData = $request->all();

        $category = StackCategory::create($validateData);

        return response()->json([
            'success'=>true,
            'message'=>'با موفقیت ثبت شد',
            'data'=>$category
        ]);

    }

    public function update(StoreStackCategoryRequest $request , $id){

        $category = StackCategory::find($id);
        if(!$category) {
            return response()->json(' دسته بندی  مورد نظر یافت نشد', 404);
        }

        $validateData = $request->all();


        $category->title = $validateData['title'];
        $category->update();

        return response()->json([
            'success'=>true,
            'message'=>'با موفقیت ویرایش  شد',
            'data'=>$category
        ]);

    }

    public function show($id){
        $category = StackCategory::find($id);
        if(!$category) {
            return response()->json(' دسته بندی  مورد نظر یافت نشد', 404);
        }
        return response()->json([
            'success'=>true,
            'data'=>$category
        ]);
    }

    public function destroy($id){
        $category = StackCategory::find($id);
        if(!$category) {
            return response()->json(' دسته بندی  مورد نظر یافت نشد', 404);
        }
        $category->delete();
        return response()->json([
            'success'=>true,
            'message'=>'با موفقیت حذف  شد',
        ]);
    }
}
