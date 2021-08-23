<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Controllers\Api\Main\PostDetailsController;

class CommentController extends Controller
{
    public function commentStore(StoreCommentRequest $request , $id){

        $validateData = $request->all();

        $post = Post::find($id);

        $comment = new Comment($validateData);
        $post->comments()->save($comment);

        $postDetails = new PostDetailsController;

        return response()->json($postDetails->showPost($id));
    }


    public function replyStore(StoreCommentRequest $request , $id){

        $validateData = $request->all();

        $comment = Comment::find($id);
        $reply = new Comment($validateData);

        //pass id of comment to reply parent_id
        $replyParent = $comment->id;
        $reply->parent_id = $replyParent;

        $reply->save();

        $commentWithReplies = Comment::with('replies.replies')->find($id);

        return response()->json($commentWithReplies);

    }


    public function commentUpdate(StoreCommentRequest $request , $id){

        $validateData = $request->all();

        $comment = Comment::find($id);
        $comment->name = $validateData['name'] ;
        $comment->email = $validateData['email'];
        $comment->comment = $validateData['comment'];

        $comment->update();

        $postDetails = new PostDetailsController;

        return response()->json($postDetails->showPost($id));
    }


    public function replyUpdate(StoreCommentRequest $request , $id){

        $validateData = $request->all();

        $reply = Comment::find($id);
        $reply->name = $validateData['name'] ;
        $reply->email = $validateData['email'];
        $reply->comment = $validateData['comment'];

        $reply->update();

        $commentWithReplies = Comment::with('replies.replies')->find($id);

        return response()->json($commentWithReplies);

    }



    public function destroyComment($id){

        $comment = Comment::find($id);
        $comment->delete();
        return response()->json([
            'success'=>'کامنت مورد نظر با موفقیت حذف شد'
        ]);
    }

    public function destroyReply($id){

        $reply = Comment::find($id);
        $reply->delete();
        return response()->json([
            'success'=>'پاسخ مورد نظر با موفقیت حذف شد'
        ]);
    }


}
