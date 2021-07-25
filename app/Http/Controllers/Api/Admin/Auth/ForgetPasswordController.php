<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Notifications\ForgetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{

    public  function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where('email' , $request->get('email'))->get();
        if($user){
            $details = [
                'greeting' => 'سلام',
                'body' =>'این ایمیل برای فراموشی رمز اکانت شما ارسال گردیده. در صورتی که از طرف شما نمی باشد آن را نادید بگیرید و در صورتی که میخواهید رمز عبور خود را عوض کنید از لینک زیر استفاده کنید',
                'text'=>'تغییر پسورد',
                'url'=>url('http://127.0.0.1:8000/api/changePassword'),
                'thanks' => 'متشکریم',
                'order_id' => 101
            ];

            Notification::send($user, new ForgetPassword($details));

            return response()->json('لینک فراموشی رمز عبور با موفقیت به ایمیل شما ارسال شد');
        }else{
            return response()->json('حساب کاربری با این آدرس ایمیل موجود نمی باشد');
        }

    }

    public  function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where()
    }


}
