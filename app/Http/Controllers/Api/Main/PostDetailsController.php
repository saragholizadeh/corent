<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Post;
use App\Http\Resources\Post as PostResources;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentCollection;

class PostDetailsController extends Controller
{
     /**
     * Disply post and comments with replies
    **/
    public function showPost($id){

        $postFind = Post::find($id);
        if(is_null($postFind)){
            return response()->json('نوشته مورد نظر یافت نشد' , 404);
        }

        $post = new PostResources(Post::find($id));

        //record new view
        $postVIew=Post::find($id);
        views($postVIew)->record();
        $views = views($postVIew)->count();

        //show comments of posts
        $comments = Comment::where('commentable_type' , 'App\Models\Post')->first();

        if($comments==NULL){
            $comments = 'there is no comment for this post yet';
        }else{
            $comments = Comment::where('commentable_type' , 'App\Models\Post')->with('replies.replies')->get();
        }

        return response()->json([
           'post'  => $post,
           'comments'=>$comments,
           'post_views'  => $views,
            ] , 200);

    }

}
