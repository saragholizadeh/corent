<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fundamental;
use Illuminate\Http\Request;

class FundamentalAdminController extends Controller
{
    public function show(){


    }

    public function destroy(){


    }

    public function changeType($id){
        $fundamental = Fundamental::find($id);
        $type = $fundamental->type;

        array_push($type , 'accepted');

        $fundamental->type = $type ;
        $fundamental->update();

        return response()->json([
            'message'=>'accepted',
        ] , 200);
    }

}
