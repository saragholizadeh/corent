<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Comment;
use App\Models\Analysis;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnalysisCollection;
use App\Http\Resources\Analysis as AnalysisResources;


class AdminAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $lastAnalyses = new AnalysisCollection( Analysis::orderBy('created_at' , 'desc')->paginate(10));

        $lastUnapprovedAnalyses =  new AnalysisCollection (Analysis::where('status' , 0)->paginate(10));

        return response()->json([
            'last_analyses' => $lastAnalyses ,
            'last_unapproved_analyses'=> $lastUnapprovedAnalyses
            ] , 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $analysis = Analysis::find($id);

        if (is_null($analysis)) {
            return response()->json('تحلیل مورد نظر یافت نشد', 404);
        }

        //show comments of posts
        $comments = Comment::where('commentable_type' , 'App\Models\Analysis')->first();

        if ($comments==null) {
            $comments = 'there is no comment for this Analysis yet';
        }else{
            $comments = Comment::where('commentable_type', 'App\Models\Analysis')->with('replies.replies')->get();
        }

        return response()->json([
           'analysis' => $analysis,
           'comments' => $comments,
        ], 200);
    }

    /**
     * approve an analysis if  is inactive by admins(status=1)
     * and reject a analysis if active(status=0)
     */
    public function Approve($id){
        $analysis = new AnalysisResources( Analysis::find($id));
        $analysis->status = 1;
        $analysis->save();
        return response()->json([
            "message" => "تایید شد",
            "data" => $analysis
        ] ,201);
    }

    public function Reject($id){
        $analysis = new AnalysisResources( Analysis::find($id));
        $analysis->status = 0;
        $analysis->save();
        return response()->json([
            "message" => "رد شد",
            "data" => $analysis
        ],200) ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $analysis = Analysis::findOrfail($id);
        if(is_null($analysis)){
            return response()->json('تحلیل مورد نظر یافت نشد' , 404);
        }
         $analysis->delete();

         return response()->json([
            "success" => true,
            "message" => "تحلیل  مورد نظر با موفقیت حذف شد",
            ] ,200);
    }
}
