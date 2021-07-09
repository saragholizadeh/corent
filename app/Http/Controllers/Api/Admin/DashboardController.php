<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){

        //show last 5 posts and print "empty" if there is no post

        $postGroup = Post::orderBy('created_at')->first();

        if(!$postGroup){
           $postGroup = "اولین نوشته را اضافه کنید";
        }
        else{
             $postGroup = Post::orderBy('created_at', 'desc')->take(5)->get();
        }


        // Show 10 new and unapproved comments
        //  print "no unapproved comment" if there is no unapproved comment

        $unapprovedComments = Comment::orderBy('created_at')->first();

        if(!$unapprovedComments){
            $unapprovedComments = "نظر تایید نشده ای وجود ندارد";
        }
        else{
            $unapprovedComments = Comment::where('status' , NULL)->orderBy('created_at' , 'desc')->take(10)->get();

        }


        // //show new and unapproved analyses




      return response()->json([ $postGroup , $unapprovedComments] , 200);
    }
}
