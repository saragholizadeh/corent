<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\User;
use App\Models\Image;
use App\Models\StackTag;
use App\Models\StackWallet;
use Illuminate\Http\Request;
use App\Models\StackQuestion;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStackQuestionRequest;
use Illuminate\Support\Facades\Validator;

class StackQuestionController extends Controller
{
    public function index()
    {
        //show all of questions title
        $questions = StackQuestion::orderBy('created_at', 'desc')->pluck('title');
        return response()->json($questions);
    }


    public function store(StoreStackQuestionRequest $request)
    {
        $validatedData = $request->all();

        $user_id = JWTAuth::user()->id;

        $validatedData['user_id'] = $user_id;

        //store images
        $files = $request->file('image');//getting post images from request
        //saving name and path of images
        if ($request->hasfile('image')) {
            foreach ($files as $file) {
                $imageName = time().rand(1, 10000).'.'.$file->extension();

                $questionId = $request->title; //post title for folder name and the images inside it
                $imagePath = public_path(). '/images/stack/questions'.$questionId;


                $file->move($imagePath, $imageName);

                $image = new Image;
                $image->image = $imageName;
                $image->path = $imagePath;

                $images[] = $image; // make an array of uploaded images
            }
        }

        $question = StackQuestion::create($validatedData); //save question

        if ($files != null) {
            $question->images()->saveMany($images);//save imageas
        }

        //update user stars and change level
        $user = User::find($user_id);
        $stars = $user->stars+2;
        $level = $user->stack_level;
        $user->stars = $stars;
        if ($stars <= 20) {
            $level = 'newcomer';
            $user->stack_level = $level;
            $user->update();
        }
        elseif ($stars <= 100 && $stars > 20 ) {
            $level = 'active';
            $user->stack_level = $level;
            $user->update();
        }
        elseif ($stars <= 1000 && $stars > 100){
            $level = 'experienced';
            $user->stack_level = $level;
            $user->update();

        }elseif ($stars <= 1500 && $stars > 1000){
            $level = 'expert';
            $user->stack_level = $level;
            $user->update();

        }elseif ($stars <= 3000 && $stars > 1500){
            $level = 'specialist';
            $user->stack_level = $level;
            $user->update();

        }elseif ($stars <= 5000 && $stars > 3000){
            $level = 'Professor';
            $user->stack_level = $level;
            $user->update();
        }
         else {
            $level = 'master';
            $user->stack_level = $level;
            $user->update();
        }

        //store tags
        $tagNames = explode(",", $request->tag);//separate tags
        $tagIds = [];

        foreach($tagNames as $tagName){
            $tag = StackTag::firstOrCreate(['tag'=>$tagName]);
            if($tag){
              $tagIds[] = $tag->id;
            }
        }
        $question->tags()->sync($tagIds);

        return response()->json([
            'success'=>true,
            'message'=> 'با موفقیت ثبت شد',
            'data'=>[$images ,$question]
        ]);
    }

    public function update(StoreStackQuestionRequest $request , $id){

        $question = StackQuestion::find($id);
        if (!$question) {
            return response()->json(' سوال  مورد نظر یافت نشد', 404);
        }

        $validateData = $request->all();

        $user_id = JWTAuth::user()->id;

        $validatedData['user_id'] = $user_id;

        $files = $request->file('image');//getting post images from request

        if ($request->hasfile('image')) {

            $questionId = $request->title; //post title for folder name and the images inside it

            //delete last Images from database for updating images
            Image::where('imageable_type', 'App\Models\StackQuestion')->where('imageable_id', $id)->delete();

            //saving name and path of images
            foreach ($files as $file) {
                $imageName = time().rand(1, 10000).'.'.$file->extension();
                $imagePath = public_path(). '/images/stack/questions'.$questionId;

                $file->move($imagePath, $imageName);

                $image = new Image;
                $image->image = $imageName;
                $image->path = $imagePath;

                $images[] = $image; // make an array of uploaded images
            }
        }

            $question->title = $validateData['title'];
            $question->body = $validateData['body'];
            $question->category_id = $validateData['category_id'];
            $question->update();


            $tagNames = explode(",", $request->tag);//separate tags

            $tagIds = [];

            foreach ($tagNames as $tagName) {
                $tag = StackTag::firstOrCreate(['tag'=>$tagName]);
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }
            dd($tagIds);

        $question->tags()->sync($tagIds);
        return response()->json([
            'success'=>true,
            'data'=>$question,
            'message'=>'با موفقیت ویرایش گردید',
        ]);
    }

    public function show($id){

        $question = StackQuestion::with('comments.replies' , 'tags')->find($id);

        $images = Image::where('imageable_type', 'App\Models\StackQuestion')->where('imageable_id', $id)->get();

        views($question)->record();
        $views = views($question)->count();

        return response()->json([
           '$question'=> $question,
            'question views'=>$views,
            'images'=>$images,
        ]);
    }

    public function destroy($id)
    {
        $question = StackQuestion::findOrfail($id);
        if(is_null($question)){
            return response()->json('سوال مورد نظر یافت نشد' , 404);
        }

         $question->with('tags')->delete();

         return response()->json([
            "success" => true,
            "message" => "سوال مورد نظر با موفقیت حذف شد",
            "data" => $question
            ]);
    }
}
