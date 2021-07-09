<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Like;
use App\Models\Dislike;
use App\Models\Analysis;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class AnalysisLikeController extends Controller
{
    public function addLike($id){

        $analysis = Analysis::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $analysis->likes; // current number of analysis likes
        $dislikes = $analysis->dislikes;// current number of analysis likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Analysis')
        ->where('user_id' , $user_id)
        ->first();


        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Analysis')
        ->where('user_id' , $user_id)
        ->first();


        if($checkUserDisiked==NULL){

            if($checkUserLiked){
                $message = 'liked before';
            }
            else{
                $like = new Like;
                $like->user_id = $user_id;
                $analysis->likes()->save($like); // create new record in likes table

                $analysis->likes = $likes+1; // add a new like in analyses table for likes column
                $analysis->update();

                $message = 'liked';
            }

        }
        else{
            $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\Analysis')
            ->where('user_id' , $user_id)
            ->delete();

            $like = new Like;
            $like->user_id = $user_id;
            $analysis->likes()->save($like); // create new record in likes table

            $analysis->dislikes = $dislikes-1;
            $analysis->likes = $likes+1; // add a new like in analyses table for likes column
            $analysis->update();

            $message = 'liked';

        }


        return response()->json($message);
    }


    public function addDislike($id){

        $analysis = Analysis::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $analysis->likes; // current number of analysis likes
        $dislikes = $analysis->dislikes;// current number of analysis likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Analysis')
        ->where('user_id' , $user_id)
        ->first();

        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Analysis')
        ->where('user_id' , $user_id)
        ->first();


        if($checkUserLiked==NULL){

            if($checkUserDisiked){
                $message = 'disliked before';
            }
            else{
                $dislike = new Dislike;
                $dislike->user_id = $user_id;
                $analysis->dislikes()->save($dislike); // create new record in likes table

                $analysis->dislikes = $dislikes+1; // add a new like in analyses table for likes column
                $analysis->update();

                $message = 'disliked';
            }
        }
        else{

            $checkUserLiked = Like::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\Analysis')
            ->where('user_id' , $user_id)
            ->delete();

            $dislike = new Dislike;
            $dislike->user_id = $user_id;
            $analysis->dislikes()->save($dislike); // create new record in likes table

            $analysis->likes = $likes-1;
            $analysis->dislikes = $dislikes+1; // add a new like in analyses table for likes column
            $analysis->update();

            $message = 'disliked';
        }
        return response()->json($message);

    }

}
