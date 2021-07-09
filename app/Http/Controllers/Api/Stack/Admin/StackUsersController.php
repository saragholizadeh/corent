<?php

namespace App\Http\Controllers\Api\Stack\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StackUsersController extends Controller
{
    public function index(){
        $users  = User::orderBy('created_at'  , 'desc')->paginate(20);
        return response()->json([
            'users'=>$users,
        ] , 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json('کاربر یافت نشد', 404);
        }
        return response()->json([
            'user'=>$user,
        ] , 200);
    }

    public function updateUserLevel(Request $request, $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'stack_level'=>'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user->stack_level = $request->stack_level;
        $user->save();

        return response()->json([
            'message'=>'تغییر پیدا کرد',
            'data'=>$user
        ] , 200);
    }


}
