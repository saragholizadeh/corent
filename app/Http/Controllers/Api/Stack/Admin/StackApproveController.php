<?php

namespace App\Http\Controllers\Api\Stack\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StackQuestion;
use App\Http\Controllers\Controller;
use App\Traits\StackLevel;

class StackApproveController extends Controller
{
    /**
     * This function is for confirming the question
     * recognizing the usefulness of the question)
     * and is approved directly by the admin
     */
    public function approveQuestion($id){

        $question = StackQuestion::find($id);
        $user_id = $question->user_id;

        $user = User::find($user_id);
        $stars = $user->stars;

        if ($question->status != 1) {
            $question->status = 1;
            $question->update();

            $user->stars = $stars+30;
            
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
