<?php

namespace App\Http\Controllers\Api\Stack\Admin;

use App\Models\User;
use App\Models\StackComment;
use Illuminate\Http\Request;
use App\Models\StackQuestion;
use App\Http\Controllers\Controller;

class StackCheckController extends Controller
{
    public function destroyQuestion($id){

        $question = StackQuestion::find($id);
        if (is_null($question)) {
            return response()->json('سوال مورد نظر یافت نشد', 404);
        }
        $question->delete();

        return response()->json([
            "success" => true,
            "message" => "سوال  مورد نظر با موفقیت حذف شد",
            ] , 200);
    }

    public function destroyComment($id){

        $comment = StackComment::find($id);
        if (is_null($comment)) {
            return response()->json('نظر مورد نظر یافت نشد', 404);
        }
        $comment->delete();

        return response()->json([
            "success" => true,
            "message" => "نظر  مورد نظر با موفقیت حذف شد",
            ] , 200);
    }

    public function banUnbanUser($id){

        $user = User::find($id);

        $userStatus = $user->stack_status;


        if($userStatus == 1){
            $user->update(['stack_status'=>0]);
            $message = 'banned';

        }else{

            $user->update(['stack_status'=>1]);
            $message = 'unbanned';
        }

        return response()->json($message , 200);
    }
}
