<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StackCategory;
use App\Models\StackQuestion;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class StackHomeController extends Controller
{
    public function index(){

        $user = JWTAuth::user();
        dd($user);

        $categories = StackCategory::all();

        $lastQuestions = StackQuestion::orderBy('created_at' , 'desc')->get();

        $bestUsers = User::where('stars' , '>=' , 1000)->get();

        $unansweredQuestions = StackQuestion::orderBy('created_at' , 'desc')
                            ->doesntHave('comments')
                            ->get();

        $populerQuetions = StackQuestion::orderBy('created_at' , 'desc')
                        ->where('likes' , '>=' ,30)
                        ->get();


        $bestQuestions = StackQuestion::orderBy('created_at' , 'desc')->where('status' , 1)->get();

        return response()->json([
            'categories'=>$categories,
            'last Questions'=>$lastQuestions,
            'best Users'=>$bestUsers,
            'Unanswered questions'=>$unansweredQuestions,
            'populer Quetions'=>$populerQuetions,
            'Best Questions'=>$bestQuestions,
        ]);
    }
}
