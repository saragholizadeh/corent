<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Regulation;
use Illuminate\Http\Request;

class RegulationDetailsController extends Controller
{
    public function index(){

        $regulations = Regulation::pluck('country');
        return response()->json($regulations);
    }

    public function show($id){
        $regulations = Regulation::find($id);
        if(is_null($regulations)){
            return response()->json('قانون گذاری مورد نظر یافت نشد' , 404);
        }

        return response()->json($regulations);

    }
}
