<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Like;
use App\Models\Post;
use App\Models\Dislike;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class PostLikeController extends Controller
{
    public function addLike($id){

        $post = Post::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $post->likes; // current number of post likes
        $dislikes = $post->dislikes;// current number of post likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Post')
        ->where('user_id' , $user_id)
        ->first();


        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Post')
        ->where('user_id' , $user_id)
        ->first();


        if($checkUserDisiked==NULL){

            if($checkUserLiked){
                $message = 'liked before';
            }
            else{
                $like = new Like;
                $like->user_id = $user_id;
                $post->likes()->save($like); // create new record in likes table

                $post->likes = $likes+1; // add a new like in posts table for likes column
                $post->update();

                $message = 'liked';
            }

        }
        else{
            $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\Post')
            ->where('user_id' , $user_id)
            ->delete();

            $like = new Like;
            $like->user_id = $user_id;
            $post->likes()->save($like); // create new record in likes table

            $post->dislikes = $dislikes-1;
            $post->likes = $likes+1; // add a new like in posts table for likes column
            $post->update();

            $message = 'liked';

        }


        return response()->json($message);
    }


    public function addDislike($id){

        $post = Post::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $post->likes; // current number of post likes
        $dislikes = $post->dislikes;// current number of post likes

        $checkUserLiked = Like::where('likeable_id' , $id)
        ->where('likeable_type' ,'App\Models\Post')
        ->where('user_id' , $user_id)
        ->first();

        $checkUserDisiked = Dislike::where('dislikeable_id' , $id)
        ->where('dislikeable_type' ,'App\Models\Post')
        ->where('user_id' , $user_id)
        ->first();


        if($checkUserLiked==NULL){

            if($checkUserDisiked){
                $message = 'disliked before';
            }
            else{
                $dislike = new Dislike;
                $dislike->user_id = $user_id;
                $post->dislikes()->save($dislike); // create new record in likes table

                $post->dislikes = $dislikes+1; // add a new like in posts table for likes column
                $post->update();

                $message = 'disliked';
            }
        }
        else{

            $checkUserLiked = Like::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\Post')
            ->where('user_id' , $user_id)
            ->delete();

            $dislike = new Dislike;
            $dislike->user_id = $user_id;
            $post->dislikes()->save($dislike); // create new record in likes table

            $post->likes = $likes-1;
            $post->dislikes = $dislikes+1; // add a new like in posts table for likes column
            $post->update();

            $message = 'disliked';
        }
        return response()->json($message);

    }

}
