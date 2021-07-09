<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\StackComment;
use Illuminate\Http\Request;
use App\Models\StackQuestion;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStackCommentRequest;
use Illuminate\Support\Facades\Validator;

class StackCommentController extends Controller
{
    public function commentStore(StoreStackCommentRequest $request , $id){
        $validatedData = $request->all();

        $user_id = JWTAuth::user()->id;

         $validatedData['user_id'] = $user_id;

         $question = StackQuestion::find($id);

         $comment = new StackComment($validatedData);
         $question->comments()->save($comment);

         return response()->json($comment);
     }

     public function replyStore(StoreStackCommentRequest $request , $id){
        $validatedData = $request->all();

        $comment = StackComment::find($id);
        $reply = new StackComment($validatedData);

        //pass id of comment to reply parent_id
        $replyParent = $comment->id;
        $reply->parent_id = $replyParent;

        $reply->save();

        return response()->json($reply);

    }


    public function commentUpdate(Request $request , $id){

        $data = $request->all();
        $validator = Validator::make($data, [
            'title'=>'required',
            'comment'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'Validation Error']);
        }

        $comment = StackComment::find($id);
        $comment->title = $request->title ;
        $comment->comment = $request->comment;

        $comment->update();

        return response()->json($comment);

    }


    public function replyUpdate(Request $request , $id){
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'comment'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'Validation Error']);
        }

        $reply = StackComment::find($id);
        $reply->title = $request->title;
        $reply->comment = $request->comment;

        $reply->update();

        return response()->json($reply);

    }



    public function destroyComment($id){

        $comment = StackComment::find($id);
        $comment->delete();
        return response()->json([
            'success'=>'کامنت مورد نظر با موفقیت حذف شد'
        ]);
    }

    public function destroyReply($id){

        $reply = StackComment::find($id);
        $reply->delete();
        return response()->json([
            'success'=>'پاسخ مورد نظر با موفقیت حذف شد'
        ]);
    }


}
