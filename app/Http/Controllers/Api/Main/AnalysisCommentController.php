<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Comment;
use App\Models\Analysis;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreStackCommentRequest;
use App\Http\Requests\StoreStackQuestionRequest;
use Illuminate\Database\Console\DbCommand;

class AnalysisCommentController extends Controller
{
    public function commentStore(StoreStackCommentRequest $request , $id){
        $validatedData = $request->all();

        $userEmail = JWTAuth::user()->email;
        $userName = JWTAuth::user()->name;
        $validatedData['email'] = $userEmail;
        $validatedData['name'] = $userName;

        $analysis = Analysis::find($id);

        $comment = new Comment($validatedData);
        $analysis->comments()->save($comment);

        return response()->json($comment);
    }

     public function replyStore(StoreStackCommentRequest $request , $id){
        $validatedData = $request->all();

        $userEmail = JWTAuth::user()->email;
        $userName = JWTAuth::user()->name;
        $validatedData['email'] = $userEmail;
        $validatedData['name'] = $userName;

        $comment = Comment::find($id);
        $reply = new Comment($validatedData);

        //pass id of comment to reply parent_id
        $replyParent = $comment->id;
        $reply->parent_id = $replyParent;

        $reply->save();

        return response()->json($reply);

    }

    public function commentUpdate(StoreStackCommentRequest $request , $id){

        $validatedData = $request->all();

        $comment = Comment::find($id);
        $comment->comment = $request->comment;

        $comment->update();

        return response()->json($comment);

    }


    public function replyUpdate(StoreStackCommentRequest $request , $id){
        $validatedData = $request->all();

        $reply = Comment::find($id);
        $reply->comment = $request->comment;

        $reply->update();

        return response()->json($reply);

    }



    public function destroyComment($id){

        $comment = Comment::find($id);
        $comment->delete();
        return response()->json([
            'success'=>'کامنت مورد نظر با موفقیت حذف شد'
        ]);
    }

    public function destroyReply($id){

        $reply = Comment::find($id);
        $reply->delete();
        return response()->json([
            'success'=>'پاسخ مورد نظر با موفقیت حذف شد'
        ]);
    }

}
