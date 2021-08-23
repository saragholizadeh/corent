<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStackAnswerRequest;
use App\Models\Image;
use App\Http\Resources\StackAnswer as StackAnswerResource;
use App\Models\StackAnswer;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class StackAnswerController extends Controller
{
   public function store(StoreStackAnswerRequest $request , $id){
       $validatedData = $request->all();

       $user_id = JWTAuth::user()->id;

       $validatedData['user_id'] = $user_id;
       $validatedData['question_id'] = (int)$id;

       //store images
       $files = $request->file('image');//getting post images from request

       //saving name and path of images
       if ($request->hasfile('image')) {
           foreach ($files as $file) {
               $imageName = time().rand(1, 10000).'.'.$file->extension();
               $imagePath = $file->store('images/stack/answers/', 'public');
               Storage::disk('public')->url($imagePath);

               $image = new Image;
               $image->image = $imageName;
               $image->path = $imagePath;

               $images[] = $image; // make an array of uploaded images
           }
       }

       $answer = new StackAnswerResource( StackAnswer::create($validatedData)); //store question

       if ($files != null) {
           $answer->images()->saveMany($images);//save images
       }else{
           $images = 'no image';
       }

       return response()->json([
           'message'=> 'با موفقیت ثبت شد',
           'answer'=>$answer,
       ] , 201);

   }

   public function update(StoreStackAnswerRequest $request , $id){
       $answer = new StackAnswerResource(StackAnswer::find($id));
       if (!$answer) {
           return response()->json('  پاسخ مورد نظر یافت نشد', 404);
       }

       $validateData = $request->all();

       $files = $request->file('image');//getting post images from request

       //delete last Images from database for updating images
       Image::where('imageable_type', 'App\Models\StackAnswer')->where('imageable_id', $id)->delete();

       if ($request->hasfile('image')) {

           //saving name and path of images
           foreach ($files as $file) {
               $imageName = time().rand(1, 10000).'.'.$file->extension();
               $imagePath = $file->store('images/answers/', 'public');
               Storage::disk('public')->url($imagePath);
               $image = new Image;
               $image->image = $imageName;
               $image->path = $imagePath;

               $images[] = $image; // make an array of uploaded images
           }
       }

       $answer->answer = $validateData['answer'];
       $answer->update();

       return response()->json([
           'message'=>'با موفقیت ویرایش گردید',
           'answer'=>$answer
       ]);
   }

    public function show($id){
        $answer =new StackAnswerResource( StackAnswer::find($id));
        if (!$answer) {
            return response()->json('  پاسخ مورد نظر یافت نشد', 404);
        }

        return response()->json(
            ['answer'=>$answer]
         , 200);
    }


   public function delete($id){

       $answer =StackAnswer::find($id);
       if (!$answer) {
           return response()->json('  پاسخ مورد نظر یافت نشد', 404);
       }
       $answer->delete();

       return response()->json(
           ['message'=>'deleted successfully']
           , 200);
   }

}
