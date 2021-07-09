<?php

namespace App\Http\Controllers\Api\Stack\Admin;

use Illuminate\Http\Request;
use App\Models\StackQuestion;
use App\Http\Controllers\Controller;
use App\Models\StackComment;

class StackDashboardController extends Controller
{
    public function index(){
        $lastQuestions = StackQuestion::orderBy('created_at' , 'desc')->get();

        $lastComments = StackComment::orderBy('created_at' , 'desc')->get();

        $populerQuetions = StackQuestion::orderBy('created_at' , 'desc')
        ->where('likes' , '>=' ,30)
        ->get();

        $unansweredQuestions = StackQuestion::orderBy('created_at' , 'desc')
                            ->doesntHave('comments')
                            ->get();

        //hottest questions ( برا ساس تعداد بالای کامنت)

        return response()->json([
            'last Questions'=>$lastQuestions,
            'lastComments'=>$lastComments,
            'Unanswered questions'=>$unansweredQuestions,
            'populer Questions'=>$populerQuetions,
        ] , 200);
    }
}
