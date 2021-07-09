<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\User;
use App\Models\StackComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StackApproveAnswerController extends Controller
{
    /**
     * This function is for approving the question as answer for question
     */
    public function approveComment($id){

        $comment = StackComment::find($id);
        $user_id = $comment->user_id ;
        $user = User::find($user_id);
        $level = $user->stack_level;
        $stars = $user->stars;

        if ($comment->status !=1) {
            $comment->status = 1;
            $comment->update();

            $user->stars = $stars + 15;

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
            $message = 'approved';
        }
        else{
            $message = 'approved before';
        }
        return response()->json($message);
    }
}

