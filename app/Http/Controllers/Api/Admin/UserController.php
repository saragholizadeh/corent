<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');

        $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc' : 'asc' ;

        $sortColumn = ltrim($sortColumn, '-');

        $user = User::orderBy($sortColumn, $sortDirection)->paginate(10);

        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->all();
        $user = User::create($validatedData);

        return response()->json([
        "success" => true,
        "message" => "با موفقیت ثبت گردید",
        "data" => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json('کاربر یافت نشد', 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function updateUserLevel(Request $request, $id)
    {
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'main_level'=>'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user->main_level = $request->main_level;
        if($request->main_level == 'admin'){
            $user->stack_level = 'admin';
        }

        $user->save();

        return response()->json([
            'message'=>'تغییر پیدا کرد',
            'data'=>$user
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrfail($id);
        if (is_null($user)) {
            return response()->json('کاربر مورد نظر یافت نشد', 404);
        }
        $user->delete();

        return response()->json([
            "success" => true,
            "message" => "کاربر  مورد نظر با موفقیت حذف شد",
            "data" => $user
            ]);
    }
}

    //what we need in or related to this controller ?
    //ban and block users
