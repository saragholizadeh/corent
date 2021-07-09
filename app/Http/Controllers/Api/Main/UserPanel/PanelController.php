<?php

namespace App\Http\Controllers\Api\Main\UserPanel;

use App\Models\User;
use App\Models\Analysis;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get user id from jwt token to show informations of user(level , ..)
        $user = User::find(JWTAuth::user()->id);

        //4 last analyses wroted by this user
        $user_id = $user->id;
        $lastAnalyses = Analysis::where('user_id' , $user_id)->first();
        if(!$lastAnalyses){
            $lastAnalyses = 'تحلیلی وجود ندارد';
        }
        else{
            $lastAnalyses = Analysis::where('user_id' , $user->id)->orderBy('created_at' , 'desc')->take(4)->get();
        }

        return response()->json([
            "success" => true,
            "user"=> $user,
            "analyses"=> $lastAnalyses,
        ]);

    }

}
