<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Http\Resources\StackCommentCollection;
use App\Models\StackComment;
use App\Models\User;
use App\Models\Image;
use App\Models\StackTag;
use App\Models\StackQuestion;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStackQuestionRequest;
use App\Http\Resources\StackQuestion as StackQuestionResources;


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
                $imagePath = $file->store('images/stack/questions/', 'public');
                Storage::disk('public')->url($imagePath);

                $image = new Image;
                $image->image = $imageName;
                $image->path = $imagePath;

                $images[] = $image; // make an array of uploaded images
            }
        }

        $question = new StackQuestionResources( StackQuestion::create($validatedData)); //store question

        if ($files != null) {
            $question->images()->saveMany($images);//save images
        }else{
            $images = 'no image';
        }

        //store tags
        //store tags
        $tagNames = explode(",", $request->tag);//separate tags
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $findTag =StackTag::where('tag' , $tagName)->first();
            if($findTag == NULL){

                $tag = StackTag::firstOrCreate(['tag' => $tagName]);
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }else{
                $tag = StackTag::where('tag' , $tagName)->first();
                $tag->count = $tag->count + 1;
                $tag->save();

                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }
        }
        $question->tags()->sync($tagIds);


        $this->userLevel();

        return response()->json([
            'success'=>true,
            'message'=> 'با موفقیت ثبت شد',
            'question'=>$question,
        ]);
    }

    public function update(StoreStackQuestionRequest $request , $id){

        $question = StackQuestion::find($id);
        if (!$question) {
            return response()->json(' سوال  مورد نظر یافت نشد', 404);
        }

        $validateData = $request->all();


        $files = $request->file('image');//getting post images from request

        if ($request->hasfile('image')) {

            $questionId = $request->title; //post title for folder name and the images inside it

            //delete last Images from database for updating images
            Image::where('imageable_type', 'App\Models\StackQuestion')->where('imageable_id', $id)->delete();

            //saving name and path of images
            foreach ($files as $file) {
                $imageName = time().rand(1, 10000).'.'.$file->extension();
                $imagePath = $file->store('images/question/', 'public');
                Storage::disk('public')->url($imagePath);
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


        //store tags
        $tagNames = explode(",", $request->tag);//separate tags
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $findTag =StackTag::where('tag' , $tagName)->first();
            if($findTag == NULL){

                $tag = StackTag::firstOrCreate(['tag' => $tagName]);
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }else{
                $tag = StackTag::where('tag' , $tagName)->first();
                $tag->count = $tag->count + 1;
                $tag->save();

                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }
        }
        $question->tags()->sync($tagIds);


        return response()->json([
            'success'=>true,
            'message'=>'با موفقیت ویرایش گردید',
            'data'=>$question,
            'tags'=>$tagNames,
        ]);
    }
    public function userLevel(){
        //update user stars and change level

        $user_id = JWTAuth::user()->id;

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
    }

    public function show($id){
        $questionFind=StackQuestion::find($id);
        if(is_null($questionFind)){
            return response()->json('سوال مورد نظر یافت نشد' , 404);
        }
        $question = new StackQuestionResources (StackQuestion::find($id));

        $comments =new StackCommentCollection(
            StackComment::where('commentable_type', 'App\Models\StackQuestion')
                ->where('commentable_id' , $id)
                ->with('replies.replies')
                ->get()
        );

        views($questionFind)->record();
        $views = views($questionFind)->count();

        return response()->json([
           'question'=> $question,
            'question_views'=>$views,
            'comments'=>$comments,
        ],200);
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
