<?php

namespace App\Http\Controllers\Api\Stack\Main;
use App\Http\Controllers\Controller;
use App\Models\StackAnswer;
use App\Models\StackQuestion;
use App\Models\StackComment;
use App\Models\StackDislike;
use App\Models\StackLike;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class StackLikeController extends Controller
{
    public function likeQuestion($id){
        $question = StackQuestion::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table

        $likes = $question->likes; // current number of question likes
        $dislikes = $question->dislikes;// current number of question likes

        $checkUserLiked = StackLike::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\StackQuestion')
            ->where('user_id' , $user_id)
            ->first();

        $checkUserDisliked = StackDislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\StackQuestion')
            ->where('user_id' , $user_id)
            ->first();

        if($checkUserDisliked==NULL){
            if($checkUserLiked){
                $message = 'The question has already been liked';
            }
            else{
                $like = new StackLike();
                $like->user_id = $user_id;
                $question->likes()->save($like); // create new record in likes table

                $question->likes = $likes+1; // add a new like in questions table for likes column
                $question->update();

                $message = 'the question has liked successfully';
            }
        }
        else{
            $checkUserDisliked->delete();
            $like = new StackLike();
            $like->user_id = $user_id;
            $question->likes()->save($like); // create new record in likes table

            $question->dislikes = $dislikes-1;
            $question->likes = $likes+1; // add a new like in questions table for likes column
            $question->update();
            $message = 'the question has liked successfully';
        }
        return response()->json($message);
    }

    public function likeComment($id){

        $comment = StackComment::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $comment->likes; // current number of comment likes
        $dislikes = $comment->dislikes;// current number of comment likes

        $checkUserLiked = StackLike::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\StackComment')
            ->where('user_id' , $user_id)
            ->first();

        $checkUserDisliked = StackDislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\StackComment')
            ->where('user_id' , $user_id)
            ->first();

        if($checkUserDisliked == NULL){
            if($checkUserLiked){
                $message = 'the comment has already been liked';
            }
            else{
                $like = new StackLike();
                $like->user_id = $user_id;
                $comment->likes()->save($like); // create new record in likes table

                $comment->likes = $likes+1; // add a new like in comments table for likes column
                $comment->update();

                $message = 'the comment has liked successfully';
            }
        }
        else{
            $checkUserDisliked->delete();

            $like = new StackLike();
            $like->user_id = $user_id;
            $comment->likes()->save($like); // create new record in likes table

            $comment->dislikes = $dislikes-1;
            $comment->likes = $likes+1; // add a new like in comments table for likes column
            $comment->update();
            $message = 'the comment has liked successfully';
        }
        return response()->json($message);
    }

    public function likeAnswer($id){
        $answer = StackAnswer::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $answer->likes; // current number of comment likes
        $dislikes = $answer->dislikes;// current number of comment likes

        $checkUserLiked = StackLike::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\StackAnswer')
            ->where('user_id' , $user_id)
            ->first();

        $checkUserDisliked = StackDislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\StackAnswer')
            ->where('user_id' , $user_id)
            ->first();

        if($checkUserDisliked==NULL){
            if($checkUserLiked){
                $message = 'the answer has already been liked';
            }
            else{
                $like = new StackLike();
                $like->user_id = $user_id;
                $answer->likes()->save($like); // create new record in likes table

                $answer->likes = $likes+1; // add a new like in comments table for likes column
                $answer->update();

                $message = 'the answer has liked successfully';
            }
        }
        else{
            $checkUserDisliked->delete();

            $like = new StackLike();
            $like->user_id = $user_id;
            $answer->likes()->save($like); // create new record in likes table

            $answer->dislikes = $dislikes-1;
            $answer->likes = $likes+1; // add a new like in answers table for likes column
            $answer->update();
            $message =  'the answer has liked successfully';
        }
        return response()->json($message);
    }

    public function dislikeQuestion($id){

        $question = StackQuestion::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $question->likes; // current number of question likes
        $dislikes = $question->dislikes;// current number of question likes

        $checkUserLiked = StackLike::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\StackQuestion')
            ->where('user_id' , $user_id)
            ->first();

        $checkUserDisliked = StackDislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\StackQuestion')
            ->where('user_id' , $user_id)
            ->first();


        if($checkUserLiked==NULL){

            if($checkUserDisliked){
                $message = 'the question has already been disliked';
            }
            else{
                $dislike = new StackDislike();
                $dislike->user_id = $user_id;
                $question->dislikes()->save($dislike); // create new record in likes table

                $question->dislikes = $dislikes+1; // add a new like in question table for likes column
                $question->update();

                $message = 'the question has disliked successfully';
            }
        }
        else{

            $checkUserLiked->delete();

            $dislike = new StackDislike();
            $dislike->user_id = $user_id;
            $question->dislikes()->save($dislike); // create new record in likes table

            $question->likes = $likes-1;
            $question->dislikes = $dislikes+1; // add a new like in question table for likes column
            $question->update();

            $message = 'the question has disliked successfully';
        }
        return response()->json($message);
    }

    public function dislikeComment($id){

        $comment = StackComment::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $comment->likes; // current number of comment likes
        $dislikes = $comment->dislikes;// current number of comment likes

        $checkUserLiked = StackLike::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\StackComment')
            ->where('user_id' , $user_id)
            ->first();

        $checkUserDisiked = StackDislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\StackComment')
            ->where('user_id' , $user_id)
            ->first();

        if($checkUserLiked==NULL){

            if($checkUserDisiked){
                $message = 'the comment has already been disliked';
            }
            else{
                $dislike = new StackDislike();
                $dislike->user_id = $user_id;
                $comment->dislikes()->save($dislike); // create new record in likes table

                $comment->dislikes = $dislikes+1; // add a new like in comments table for likes column
                $comment->update();

                $message = 'the comment has disliked successfully';
            }
        }
        else{

            $checkUserLiked->delete();

            $dislike = new StackDislike();
            $dislike->user_id = $user_id;
            $comment->dislikes()->save($dislike); // create new record in likes table

            $comment->likes = $likes-1;
            $comment->dislikes = $dislikes+1; // add a new like in comments table for likes column
            $comment->update();

            $message = 'the comment has disliked successfully';
        }
        return response()->json($message);

    }

    public function dislikeAnswer($id){

        $answer = StackAnswer::find($id);

        $user_id = JWTAuth::user()->id;//get userId from token for store new record in likes table
        $likes = $answer->likes; // current number of answer likes
        $dislikes = $answer->dislikes;// current number of answer likes

        $checkUserLiked = StackLike::where('likeable_id' , $id)
            ->where('likeable_type' ,'App\Models\StackAnswer')
            ->where('user_id' , $user_id)
            ->first();

        $checkUserDisiked = StackDislike::where('dislikeable_id' , $id)
            ->where('dislikeable_type' ,'App\Models\StackAnswer')
            ->where('user_id' , $user_id)
            ->first();

        if($checkUserLiked==NULL){

            if($checkUserDisiked){
                $message = 'the answer has already been disliked';
            }
            else{
                $dislike = new StackDislike();
                $dislike->user_id = $user_id;
                $answer->dislikes()->save($dislike); // create new record in likes table

                $answer->dislikes = $dislikes+1; // add a new like in answers table for likes column
                $answer->update();

                $message = 'the answer has disliked successfully';
            }
        }
        else{

            $checkUserLiked->delete();

            $dislike = new StackDislike();
            $dislike->user_id = $user_id;
            $answer->dislikes()->save($dislike); // create new record in likes table

            $answer->likes = $likes-1;
            $answer->dislikes = $dislikes+1; // add a new like in answers table for likes column
            $answer->update();

            $message = 'the answer has disliked successfully';
        }
        return response()->json($message);
    }

    public function userLevel(){
        //update user stars and change level

        $user_id = JWTAuth::user()->id;
        $user = User::find($user_id);
        $stars = $user->stars+2;
        $level = $user->stack_level;
        $user->stars = $stars;
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
    }


}
