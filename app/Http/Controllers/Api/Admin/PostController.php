<?php

namespace App\Http\Controllers\Api\Admin;

use Fo;
use App\Models\Post;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResources;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $sortColumn = $request->input('sort' , 'created_at');
        $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc' : 'asc' ;
        $sortColumn = ltrim($sortColumn , '-');
        $post = Post::orderBy($sortColumn , $sortDirection)->paginate(10);

        return response()->json($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StorePostRequest $request){

        $validatedData = $request->all();

        $user_id = JWTAuth::user()->id;

        $validatedData['user_id'] = $user_id;

        if ($request->hasfile('image')) {

            $files = $request->file('image');//getting post images from request

        //saving name and path of images
            foreach ($files as $file) {
                $imageName = time().rand(1, 10000).'.'.$file->extension();
                $postTitle = $request->title; //post title for folder name and the images inside it
                $imagePath = $file->store('images/posts/'.$postTitle, 'public');
                Storage::disk('public')->url($imagePath);
                $image = new Image;
                $image->image = $imageName;
                $image->path = $imagePath;

                $images[] = $image; // make an array of uploaded images
            }
        }

        $post = new PostResources (Post::create($validatedData));

        if($files != NULL ){
        $post->images()->saveMany($images);//save images in image table
        };

    	$tags = explode(",", $request->tags);//separate tags

        $post->tag($tags);//save tags in tags table


//        $imageShow = Image::where('imageable_type' , 'App\Models\Post')->where('imageable_id' , $post->id)->get();


        return response()->json([
        'message'=>'created successfully',
            'post'=>$post,
            'images'=>$images,
        ] , 200);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $post = Post::find($id);

        if(is_null($post)){
            return response()->json('پست  مورد نظر یافت نشد' , 404);
        }
        return response()->json([
            'post' => $post,
            ] , 200);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(StorePostRequest $request , $id )
    {
        $post_failed = Post::find($id);
        if (is_null($post_failed)) {
            return response()->json(' پست  مورد نظر یافت نشد', 404);
        }

        $validatedData = $request->all();

        $user_id = JWTAuth::user()->id;

        $validatedData['user_id'] = $user_id;

        $tags = explode(",", $request->tags);

        if ($request->hasfile('image')) {

            $postTitle = $request->title; //post title for folder name and the images inside it

            //delete last Images from database for updating images
            Image::where('imageable_type' , 'App\Models\Post')->where('imageable_id' , $id)->delete();


            $files = $request->file('image');

            foreach ($files as $file) {

                $imageName = time().rand(1,10000).'.'.$file->extension();

                $postTitle = $request->title; //post title for folder name and the images inside it
                $imagePath = $file->store('images/posts/'.$postTitle, 'public');
                Storage::disk('public')->url($imagePath);

                $image = new Image;
                $image->image = $imageName;
                $image->path = $imagePath;

                $images[] = $image; // make an array of uploaded images
            }
        }

        $post = Post::find($id);

        $post->category_id = $validatedData['category_id'];
        $post->title = $validatedData['title'];
        $post->body = $validatedData['body'];
        $post->study_time = $validatedData['study_time'];
        $post->tags = $validatedData['tags'];

        $post->save();

        $post->images()->saveMany($images);

        $post->retag($tags); // delete current tags and save new tags
        $post->tag($tags);


        return response()->json([
        "success" => true,
        "message" => "با موفقیت ویرایش گردید",
        "data" => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = Post::findOrfail($id);
        if(is_null($post)){
            return response()->json('دسته بندی مورد نظر یافت نشد' , 404);
        }
         $post->delete();

         return response()->json([
            "success" => true,
            "message" => "دسته بندی مورد نظر با موفقیت حذف شد",
            "data" => $post
            ]);
    }
}

