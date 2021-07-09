<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Urequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserFormRequestController extends Controller
{
    public function Approve($id){

        $formRequest = Urequest::find($id);
        $user = User::find($formRequest->user_id);

        $formRequest->status = 1;
        $formRequest->update();

        if($formRequest->request_type == 'author'){
            $user->main_level = 'author';
        }else{
            $user->main_level = 'signal_seller';
        }
        $user->update();

        return response()->json([
            "message" => "تایید شد",
            "user" => $user ,
            "formRequest" => $formRequest
        ]);
    }

    public function Reject($id){
        return response()->json([
            "message" => "رد شد",
        ]);
    }
}
