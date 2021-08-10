<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResources;

class AdminCommentController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function lastComments()
    {
        $postComments = Comment::where('imageable_type', 'App\Models\Post')->orderBy('created_at', 'desc')->latest(5);

        $analysisComments = Comment::where('imageable_type', 'App\Models\Analysis')->orderBy('created_at', 'desc')->latest(5);


        return response()->json([
            'post_comments'=>$postComments,
            'analysis_comments'=>$analysisComments,
        ], 200);
    }


     /**
     * approve a comment if  is inactive by admins(status=1)
     * and reject a comment if active(status=0)
     * these methods are for replies too
     */
    public function Approve($id){
        $comment = Comment::find($id);
        $comment->status = 1;
        $comment->save();
        return response()->json([
            "message" => "تایید شد",
            "comment" => $comment
        ],200);
    }

    public function Reject($id){
        $comment = Comment::find($id);
        $comment->status = 0;
        $comment->save();
        return response()->json([
            "message" => "رد شد",
            "comment" => $comment
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comment =new CommentResources (Comment::with('replies.replies')->find($id));
        if(is_null($comment)){
            return response()->json('کامنت مورد نظر یافت نشد' , 404);
        }

        return response()->json([
           'comment' => $comment
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrfail($id);
        if(is_null($comment)){
            return response()->json('کامنت مورد نظر یافت نشد' , 404);
        }
         $comment->delete();

         return response()->json([
            "message" => "کامنت  مورد نظر با موفقیت حذف شد",
            ],200);
    }
}
