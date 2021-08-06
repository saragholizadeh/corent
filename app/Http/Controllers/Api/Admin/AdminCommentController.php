<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


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


    public function postIndex(){
        $postComments = Comment::where('imageable_type', 'App\Models\Post')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return response()->json([
            'post_comments'=>$postComments,
        ], 200);
    }


    public function analysisIndex(){
        $analysisComments = Comment::where('imageable_type', 'App\Models\Analysis')->orderBy('created_at', 'desc')->paginate(20);

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
            "data" => $comment
        ]);
    }

    public function Reject($id){
        $comment = Comment::find($id);
        $comment->status = 0;
        $comment->save();
        return response()->json([
            "message" => "رد شد",
            "data" => $comment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comment = Comment::with('replies')->find($id);
        if(is_null($comment)){
            return response()->json('کامنت مورد نظر یافت نشد' , 404);
        }

        return response()->json($comment , 200);
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
            "success" => true,
            "message" => "کامنت  مورد نظر با موفقیت حذف شد",
            ]);
    }
}
