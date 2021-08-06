<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::orderBy('created_at' , 'desc')->paginate(5);

        return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->all();

        $image = new Image;
        $getImage = $request->image;

        $imageName = time().'.'.$getImage->extension();
        $productTitle = $request->title;
        $imagePath = $getImage->store('images/products/'.$productTitle, 'public');
        Storage::disk('public')->url($imagePath);
        $image->image = $imageName;

        $product = Product::create($validatedData);

        $product->image()->save($image);

        return response()->json([
            'success'=>true,
            'data'=>$product
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('plans')->find($id);

        if(is_null($product)){
            return response()->json('محصول مورد نظر یافت نشد' , 404);
        }
        return response()->json($product , 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, $id)
    {
        $validatedData = $request->all();

        $productTitle = $request->title;
        Image::where('imageable_type' , 'App\Models\Product')->where('imageable_id' , $id)->delete();

        $image = new Image;
        $getImage = $request->image;

        $imageName = time().'.'.$getImage->extension();
        $imagePath = $getImage->store('images/products/'.$productTitle, 'public');
        Storage::disk('public')->url($imagePath);

        $image->path = $imagePath;
        $image->image = $imageName;

        $product = Product::find($id);
        $product->title = $validatedData['title'];
        $product->description =  $validatedData['description'];
        $product->update();

        $product->image()->save($image);

        return response()->json([
            "success" => true,
            "message" => "با موفقیت ویرایش گردید",
            "data" => $product
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrfail($id);
        if(is_null($product)){
            return response()->json('محصول  مورد نظر یافت نشد' , 404);
        }
         $product->delete();

         return response()->json([
            "success" => true,
            "message" => "محصول  مورد نظر با موفقیت حذف شد",
            ]);
    }
}
