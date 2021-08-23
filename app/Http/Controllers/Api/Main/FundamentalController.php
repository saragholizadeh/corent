<?php

namespace App\Http\Controllers\Api\Main;

use App\Models\Fundamental;
use Illuminate\Http\Request;
use App\Http\Resources\Fundamental as FundamentalResources;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFundamentalRequest;

class FundamentalController extends Controller
{
    public function index(){

    }

    public function store(StoreFundamentalRequest $request){
        $validatedData = $request->all();

        $user_id = JWTAuth::user()->id;

        $validatedData['user_id'] = $user_id;

        $fundamental = new FundamentalResources (Fundamental::create($validatedData));

        return response()->json([
            'message' => 'با موفقیت ثبت شد',
            'data' => $fundamental,
        ] , 200);

    }

    public function update(StoreFundamentalRequest $request ,  $id){

        $validatedData = $request->all();

        $fundamental = new FundamentalResources (Fundamental::find($id));

        $fundamental->currency = $validatedData['currency'];
        $fundamental->event_date = $validatedData['event_date'];
        $fundamental->description = $validatedData['description'];

        $fundamental->update();

        return response()->json([
            'message' => 'با موفقیت ویرایش شد',
            'data' => $fundamental
        ], 200);

    }

    public function show($id){
        $fundamental = new FundamentalResources (Fundamental::find($id));

        return response()->json([
            'success'=>true,
            'data'=>$fundamental,

        ], 200);
    }

    public function destroy($id){
            $fundamental = Fundamental::find($id);
            $fundamental->delete();
            return response()->json([
                'success'=>true,
                'message'=>'اخبار مورد نظر با موفقیت حذف شد'
            ] , 200);
    }

}
