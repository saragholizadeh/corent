<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Mail\VerifyMail;
use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerifyController extends Controller
{

    public function sendNotification(){

    $random = $this->generateVerificationCode(6);

            $details = [
                'title' => 'Mail from ItSolutionStuff.com',
                'body' =>$random,
            ];

            Mail::to('calikeax@gmail.com')->send(new VerifyMail($details));

        return response()->json([
            'message'=>'your email verification code sent to your email'
        ] , 201);
    }

    public function generateVerificationCode($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }

        $findToken = Token::where('user_id' , JWTAuth::user()->id)->first();
        if(is_null($findToken)){
            $token = new Token();
            $token->user_id = JWTAuth::user()->id;
            $token->code = $code;
            $token->status = 0;
            $token->save();
        }else{
            $findToken = Token::where('user_id' , JWTAuth::user()->id)->delete();
            $token = new Token();
            $token->user_id = JWTAuth::user()->id;
            $token->code = $code;
            $token->status = 0;
            $token->save();
            $this->deleteToken();
        }
        return $code;
    }

    public function deleteToken(){
        $token = Token::where('created_at', '<', Carbon::now()->subDays(2))->delete;
    }

    public function emailVerify(Request $request){
        $data = $request->validate([
            'code' => 'required|size:10|numeric',
        ]);
        $interedCode = (int)$data['code'];//convert code from string to integer

        $userCode = Token::where('user_id' , JWTAuth::user()->id)->first();//find user from tokens table
        $activationCode = $userCode->code; //get activation code of user in tokens table
        $expires_in = (int)$userCode->expires_in; //get expire time of code
        $new_code_time = (int)$userCode->new_code_time; //get delay time for new activation code request
        $now = Carbon::now()->timestamp;

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
