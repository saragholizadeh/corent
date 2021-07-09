<?php

namespace App\Http\Controllers\Api\Main\UserPanel;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUformRequest;
use App\Models\Urequest;

class RequestFormController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUformRequest $request)
    {
        $validatedData = $request->all();

        $user_id = JWTAuth::user()->id;
        $validatedData['user_id'] = $user_id;

        $formRequest = Urequest::create($validatedData);

        return response()->json([
            'form reuest'=>$formRequest,
            'message' => 'created successfully'
        ] , 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $formRequest = Urequest::find($id);
        $formRequest->delete();
        return response()->json([
            'message'=>'deleted successfullly',
        ] , 200);
    }
}
