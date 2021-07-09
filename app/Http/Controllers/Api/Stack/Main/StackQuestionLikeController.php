<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StackQuestion;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class StackQuestionLikeController extends Controller
{
    public function addLike($id){

        $question = StackQuestion::find($id);

        $likes = $question->likes;
        $dislikes = $question->dislikes;
        $user_id = JWTAuth::user()->id;

        //If a user likes this question,
        // 5 stars will be added to the user who posted this question

        $question_userId = $question->user_id;//Get user_id of question for add stars to this user
        $user = User::find($question_userId);//Find the id of user to access the users table and number of stars
        $stars = $user->stars;
        $level = $user->stack_level;

        if (!in_array($user_id, $dislikes)) {

            if (!in_array($user_id, $likes)) { //check user id in likes array

                array_push($likes, $user_id); // add new user id to likes

                $question->likes = $likes;
                $question->update();

                $user->stars = $stars+5;// add 5 stars

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
                $message = "liked";
            }
            else{
                $message = "liked before";
            }
        }
        else{
            $search_userId = array_search($user_id, $dislikes);
            array_splice($dislikes, $search_userId , 1);
            array_push($likes, $user_id); // add new user id to likes

            $question->dislikes = $dislikes;
            $question->likes = $likes;
            $question->update();

            $message = "liked";
        }

        return response()->json($message);
    }


    public function addDislike($id){

        $question = StackQuestion::find($id);

        $dislikes = $question->dislikes;
        $likes = $question->likes;
        $user_id = JWTAuth::user()->id;

        if (!in_array($user_id, $likes)) {
            //check user id in dislikes array
            if (!in_array($user_id, $dislikes)) {

                array_push($dislikes, $user_id); // add new user id to dislikes
                $question->dislikes = $dislikes;
                $question->update();
                $message = "disliked";
            } else {
                $message = "disliked before";
            }
        }
        else{
            $search_userId = array_search($user_id, $likes);
            array_splice($likes, $search_userId , 1);
            array_push($dislikes, $user_id); // add new user id to likes

            $question->dislikes = $dislikes;
            $question->likes = $likes;
            $question->update();

            $message = "disliked";


        }
        return response()->json($message);

    }
}
