<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanRequest;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function index(){
        $plan = Plan::orderBy('created_at' , 'desc')->paginate(6);
        return response()->json($plan);
    }

    public function store(StorePlanRequest $request){

        $validateData = $request->all();
        $plan = Plan::create($validateData);

        return response()->json([
            'success'=>true,
            'data'=>$plan,
        ]);

    }

    public function update(StorePlanRequest $request , $id){
        $planFaild = Plan::find($id);
        if (!$planFaild) {
            return response()->json(' پلن  مورد نظر یافت نشد', 404);
        }

        $validateData = $request->all();

        $plan = Plan::find($id);
        $plan->title = $validateData['title'];
        $plan->description = $validateData['description'];
        $plan->price = $validateData['price'];
        $plan->product_id = $validateData['product_id'];
        $plan->update();

        return response()->json([
            'success'=>true,
            'data'=>$plan,
        ]);

    }

    public function show($id){
        $plan = Plan::find($id);
        if(!$plan){
            return response()->json(' پلن  مورد نظر یافت نشد', 404);
        }
        return response()->json($plan);
    }

    public function destroy($id){
        $plan = Plan::find($id);
        if(!$plan){
            return response()->json(' پلن  مورد نظر یافت نشد', 404);
        }
        $plan->delete();
        return response()->json('پلن مورد نظر حذف شد');

    }
}
