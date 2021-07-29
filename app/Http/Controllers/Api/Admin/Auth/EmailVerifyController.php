<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use App\Notifications\EmailVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerifyController extends Controller
{

    public function sendNotification(){

    $user = JWTAuth::user();
    $name = JWTAuth::user()->name;
    $id = JWTAuth::user()->id;

    $this->checkTokenExist();

    $code = Token::where('user_id' , $id)->pluck('code');
//    dd($code);

                $details = [
                    'greeting' => 'سلام'.$name,
                    'body' => 'لطفا از کد زیر برای تایید ایمیل خود و فعالسازی حساب کاربری خود استفاده کنید',
                    'activation_code'=>$code,
                    'thanks' => 'متشکریم',
                    'order_id' => 101
                ];
        Notification::send($user, new EmailVerification($details));


        return response()->json([
            'message'=>'your email verification code sent to your email'
        ] , 201);
    }

    public function checkTokenExist(){
        $id = JWTAuth::user()->id;

        $findToken = Token::where('user_id' , $id)->first();
        if(is_null($findToken)){
            $token = new Token();
            $token->user_id = $id;
            $token->code = $this->generateVerificationCode();
            $token->status = 0;
            $token->expires_in = Carbon::now()->addMinutes(10);
            $token->new_code_date = Carbon::now()->addMinutes(2);
            $token->save();
        }else{
            Token::where('user_id' , $id)->delete();
            $token = new Token();
            $token->user_id = $id;
            $token->code = $this->generateVerificationCode() ;
            $token->status = 0;
            $token->status = 0;
            $token->expires_in = Carbon::now()->addMinutes(10);
            $token->new_code_date = Carbon::now()->addMinutes(2);
            $token->save();
        }
    }

    public function generateVerificationCode($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }

        return $code;
    }


    public function emailVerify(Request $request){
        $data = $request->validate([
            'code' => 'required|numeric',
        ]);
        $interedCode = (int)$data['code'];//convert code from string to integer

        $userCode = Token::where('user_id' , JWTAuth::user()->id)->first();//find user from tokens table
        $activationCode = $userCode->code; //get activation code of user in tokens table
        $expires_in = (int)$userCode->expires_in; //get expire time of code
        $new_code_time = (int)$userCode->new_code_time; //get delay time for new activation code request
        $now = Carbon::now()->timestamp;

//        dd(['expire :'.$expires_in , 'now :'.$now , 'created: '.$userCode->created_at]);
        if($interedCode == $activationCode) {
            if ($now < $expires_in) {
                if ($now > $new_code_time) {
                    $user = JWTAuth::user()->id;
                    $findUser = User::find($user);
                    $findUser->email_verified_at = Carbon::now()->timestamp;
                    $findUser->save();

                    $token = Token::where('user_id', JWTAuth::user()->id)->first();
                    $token->status = 1;
                    $token->save();
                    return response()->json('email verified successfully', 200);
                } else {
                    return response()->json('wait 2 minutes for new activation code');
                }
            } else {
                return response()->json('code expired', 400);
            }
        }else{
            return response()->json('wrong activation code' , 400);
        }
    }

}
