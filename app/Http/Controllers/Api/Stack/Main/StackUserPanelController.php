<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\User;
use App\Models\Image;
use App\Models\StackComment;
use Illuminate\Http\Request;
use App\Models\StackQuestion;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StackUserPanelController extends Controller
{
    public function index(){

        //get user id from jwt token to show informations of user(level , ..)
        $user = User::with('image')->find(JWTAuth::user()->id);

        $user_id = $user->id;

        //4 last questions wroted by this user
        $lastQuestions = StackQuestion::where('user_id' , $user_id)->first();
        if(!$lastQuestions){

            $lastQuestions = 'سوالی وجود ندارد';
            $questionCount = 0;

        }
        else{

            $lastQuestions = StackQuestion::where('user_id' , $user->id)->orderBy('created_at' , 'desc')->take(4)->get();
            $questionCount = StackQuestion::where('user_id' , $user->id)->count();

        }

        //4 last comments wroted by this user
        $lastComments = StackComment::where('user_id' , $user_id)->first();

        if(!$lastComments){

            $lastComments = 'نظری وجود ندارد';
            $commentCount = 0;

        }
        else{

            $lastComments = StackComment::where([
                ['user_id' ,'=', $user_id],
                ['parent_id' ,'=', NULL],
            ])->orderBy('created_at' , 'desc')->take(4)->get();

            $commentCount = StackComment::where([
                ['user_id' ,'=', $user_id],
                ['parent_id' ,'=', NULL],
            ])->count();
        }


        return response()->json([
            "success" => true,
            "user"=> $user,
            "last Questions"=> $lastQuestions,
            "last comments"=>$lastComments,
            "comment Count"=>$commentCount,
            "question Count"=>$questionCount,
        ]);
    }


    public function update(Request $request){

        $data = $request->all();
        $user = User::find(JWTAuth::user()->id);
        $user_id = $user->id;

        $validator = Validator::make($data ,[
            'name'=>'required|string',
            'location'=>'nullable|string',
            'email'=>'required|email',
            'bio'=>'nullable|string',
            'phone_number'=>'required|integer',
            'bitcoin'=>'nullable|string',
            'ethereum'=>'nullable|string',
            'tether'=>'nullable|string',
            'bnb'=>'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'خطا در اعتبار سنجی']);
        }


        //delete last Images from database for updating images
        Image::where('imageable_type' , 'App\Models\User')->where('imageable_id' , $user_id)->delete();

        $image = new Image;
        $getImage = $request->image;
        $imageName = time().'.'.$getImage->extension();

        $username = JWTAuth::user()->username;
        $imagePath = public_path(). '/images/users/avatars/'.$username;

        $image->path = $imagePath;
        $image->image = $imageName;

        $getImage->move($imagePath, $imageName);

        $user->name = $request->name;
        $user->location = $request->location;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->bitcoin = $request->bitcoin;
        $user->ethereum = $request->ethereum;
        $user->tether = $request->tether;
        $user->bnb = $request->bnb;
        $user->phone_number = $request->phone_number;
        $user->update();

        $user->image()->save($image);

        return response()->json([
            "success" => true,
            "data" => $user
            ] , 200);
    }
}
