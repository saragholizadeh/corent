<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\StackAnswer;
use App\Models\StackComment;
use App\Models\StackQuestion;
use Illuminate\Support\Facades\Request;
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

        $current_url = Request::url();
        $question_url = "http://127.0.0.1:8000/api/stack/addComment/question/".$id;
        $answer_url = "http://127.0.0.1:8000/api/stack/addComment/answer/".$id;

        if($current_url == $question_url){
            $question = StackQuestion::find($id);
            $comment = new StackComment($validatedData);
            $question->comments()->save($comment);
            return response()->json([
                'question comment'=>$comment
            ],201);
        }elseif($current_url == $answer_url){
            $answer = StackAnswer::find($id);
            $comment = new StackComment($validatedData);
            $answer->comments()->save($comment);
            return response()->json([
                'answer comment'=>$comment
            ],201);
        }else{
            return response('error');
        }
    }


    public function commentUpdate(StoreStackCommentRequest $request , $id){

        $comment = StackComment::find($id);

        $validatedData = $request->all();

        $comment-> comment =$validatedData['comment'];
        $comment->update();

        return response()->json($comment , 200);
    }

    public function deleteComment($id){

        $comment = StackComment::find($id);
        $comment->delete();
        return response()->json([
            'success'=>'کامنت مورد نظر با موفقیت حذف شد'
        ]);
    }

}
