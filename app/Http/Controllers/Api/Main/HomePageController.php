<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public  function index(){
        $token = Token::all();
        return response()->json($token);
    }
}
