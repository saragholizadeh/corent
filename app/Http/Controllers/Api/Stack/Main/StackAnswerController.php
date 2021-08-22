<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStackAnswerRequest;
use App\Models\Image;
use App\Http\Resources\StackAnswer as StackAnswerResource;
use App\Models\StackAnswer;
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
    $answer = StackAnswer::find($id);

   }

   public function delete(){

   }

   public function userAnswers(){

   }
}
