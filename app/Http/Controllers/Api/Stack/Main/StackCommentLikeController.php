<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\User;
use App\Models\StackComment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class StackCommentLikeController extends Controller
{
    public function addLike($id){

        $comment = StackComment::find($id);

        $likes = $comment->likes;
        $dislikes = $comment->dislikes;


        $user_id = JWTAuth::user()->id;

         //If a user likes this comment,
        // 10 stars will be added to the user who posted this comment

        $comment_userId = $comment->user_id;//Get user_id of comment for add stars to this user
        $user = User::find($comment_userId);//Find the id of user to access the users table and number of stars
        $stars = $user->stars;
        $level = $user->stack_level;


        if (!in_array($user_id, $dislikes)) {

            if (!in_array($user_id, $likes)) { //check user id in likes array

                array_push($likes, $user_id); // add new user id to likes

                $comment->likes = $likes;
                $comment->update();

                $user->stars = $stars+10;// add 10 stars

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

            $comment->dislikes = $dislikes;
            $comment->likes = $likes;
            $comment->update();

            $message = "liked";
        }

        return response()->json($message);
    }


    public function addDislike($id){

        $comment = StackComment::find($id);

        $dislikes = $comment->dislikes;
        $likes = $comment->likes;
        $user_id = JWTAuth::user()->id;

        if (!in_array($user_id, $likes)) {
            if (!in_array($user_id, $dislikes)) {//check user id in dislikes array

                array_push($dislikes, $user_id); // add new user id to dislikes
                $comment->dislikes = $dislikes;
                $comment->update();
                $message = "disliked";
            } else {
                $message = "disliked before";
            }
        }
        else{
            $search_userId = array_search($user_id, $likes);
            array_splice($likes, $search_userId , 1);
            array_push($dislikes, $user_id); // add new user id to likes

            $comment->dislikes = $dislikes;
            $comment->likes = $likes;
            $comment->update();

            $message = "disliked";


        }
        return response()->json($message);

    }
}
