<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Like;
use App\Models\Comment;
use App\Models\Dislike;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class CommentLikeController extends Controller
{
    public function addLike($id){

        $comment = Comment::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $comment->likes; // current number of comment likes
        $dislikes = $comment->dislikes;// current number of comment likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Comment')
        ->where('user_id' , $user_id)
        ->first();

        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Comment')
        ->where('user_id' , $user_id)
        ->first();

        if($checkUserDisiked==NULL){
            if($checkUserLiked){
                $message = 'liked before';
            }
            else{
                $like = new Like;
                $like->user_id = $user_id;
                $comment->likes()->save($like); // create new record in likes table

                $comment->likes = $likes+1; // add a new like in comments table for likes column
                $comment->update();

                $message = 'liked';
            }
        }
        else{
            $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\Comment')
            ->where('user_id' , $user_id)
            ->delete();

            $like = new Like;
            $like->user_id = $user_id;
            $comment->likes()->save($like); // create new record in likes table

            $comment->dislikes = $dislikes-1;
            $comment->likes = $likes+1; // add a new like in comments table for likes column
            $comment->update();
            $message = 'liked';
        }
        return response()->json($message);
    }


    public function addDislike($id){

        $comment = Comment::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $comment->likes; // current number of comment likes
        $dislikes = $comment->dislikes;// current number of comment likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Comment')
        ->where('user_id' , $user_id)
        ->first();

        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Comment')
        ->where('user_id' , $user_id)
        ->first();


        if($checkUserLiked==NULL){

            if($checkUserDisiked){
                $message = 'disliked before';
            }
            else{
                $dislike = new Dislike;
                $dislike->user_id = $user_id;
                $comment->dislikes()->save($dislike); // create new record in likes table

                $comment->dislikes = $dislikes+1; // add a new like in comments table for likes column
                $comment->update();

                $message = 'disliked';
            }
        }
        else{

            $checkUserLiked = Like::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\Comment')
            ->where('user_id' , $user_id)
            ->delete();

            $dislike = new Dislike;
            $dislike->user_id = $user_id;
            $comment->dislikes()->save($dislike); // create new record in likes table

            $comment->likes = $likes-1;
            $comment->dislikes = $dislikes+1; // add a new like in comments table for likes column
            $comment->update();

            $message = 'disliked';
        }
        return response()->json($message);

    }

}
