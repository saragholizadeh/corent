<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Like;
use App\Models\Dislike;
use App\Models\Fundamental;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class FundamentalLikeController extends Controller
{
    public function addLike($id){

        $fundamental = Fundamental::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $fundamental->likes; // current number of fundamental likes
        $dislikes = $fundamental->dislikes;// current number of fundamental likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Fundamental')
        ->where('user_id' , $user_id)
        ->first();


        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Fundamental')
        ->where('user_id' , $user_id)
        ->first();


        if($checkUserDisiked==NULL){

            if($checkUserLiked){
                $message = 'liked before';
            }
            else{
                $like = new Like;
                $like->user_id = $user_id;
                $fundamental->likes()->save($like); // create new record in likes table

                $fundamental->likes = $likes+1; // add a new like in fundamentals table for likes column
                $fundamental->update();

                $message = 'liked';
            }

        }
        else{
            $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\Fundamental')
            ->where('user_id' , $user_id)
            ->delete();

            $like = new Like;
            $like->user_id = $user_id;
            $fundamental->likes()->save($like); // create new record in likes table

            $fundamental->dislikes = $dislikes-1;
            $fundamental->likes = $likes+1; // add a new like in fundamentals table for likes column
            $fundamental->update();

            $message = 'liked';

        }


        return response()->json($message);
    }


    public function addDislike($id){

        $fundamental = Fundamental::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $fundamental->likes; // current number of fundamental likes
        $dislikes = $fundamental->dislikes;// current number of fundamental likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Fundamental')
        ->where('user_id' , $user_id)
        ->first();

        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Fundamental')
        ->where('user_id' , $user_id)
        ->first();


        if($checkUserLiked==NULL){

            if($checkUserDisiked){
                $message = 'disliked before';
            }
            else{
                $dislike = new Dislike;
                $dislike->user_id = $user_id;
                $fundamental->dislikes()->save($dislike); // create new record in likes table

                $fundamental->dislikes = $dislikes+1; // add a new like in fundamentals table for likes column
                $fundamental->update();

                $message = 'disliked';
            }
        }
        else{

            $checkUserLiked = Like::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\Fundamental')
            ->where('user_id' , $user_id)
            ->delete();

            $dislike = new Dislike;
            $dislike->user_id = $user_id;
            $fundamental->dislikes()->save($dislike); // create new record in likes table

            $fundamental->likes = $likes-1;
            $fundamental->dislikes = $dislikes+1; // add a new like in fundamentals table for likes column
            $fundamental->update();

            $message = 'disliked';
        }
        return response()->json($message);

    }

}
