<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $email = $request->get('email');
        $user = User::where('email' , $email)->first();
        if($user){
            $token = str_random(64);
            DB::table('password_resets')->insert(
                ['email' => $email, 'token' => $token]
            );
            $details = [
                'greeting' => 'سلام',
                'body' =>'این ایمیل برای فراموشی رمز اکانت و یا تغییر رمز عبور شما ارسال گردیده. در صورتی که از طرف شما نمی باشد آن را نادید بگیرید و در صورتی که میخواهید رمز عبور خود را تغییر دهید از لینک زیر استفاده کنید',
                'text'=>'تغییر پسورد',
                'url'=>url('http://127.0.0.1:8000/api/changePassword?token='.$token.'&email='.$email),
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
        $updatePassword = DB::table('password_resets')
            ->where(['email' => $request->email, 'token' => $request->token])
            ->first();
        if(!$updatePassword){
            return  response()->json('invalid url');
        }else{
            User::where('email' , $request->email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where(['email'=> $request->email])->delete();

            return response()->json('your password updated successfully' , 200);
        }
    }


}
