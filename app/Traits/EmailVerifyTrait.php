<?php


namespace App\Traits;

use App\Mail\VerifyMail;
use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

trait EmailVerifyTrait
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

//        dd(auth()->loginUsingId());
//        $findToken = Token::where('user_id' , JWTAuth::user()->id)->first();
//        if(is_null($findToken)){
//            $token = new Token();
//            $token->user_id = JWTAuth::user()->id;
//            $token->code = $code;
//            $token->status = 0;
//            $token->save();
//        }else{
//            $findToken = Token::where('user_id' , JWTAuth::user()->id)->delete();
//            $token = new Token();
//            $token->user_id = JWTAuth::user()->id;
//            $token->code = $code;
//            $token->status = 0;
//            $token->save();
//            $this->deleteToken();
//        }
        return $code;
    }

    public function deleteToken(){
        Token::where('created_at', '<', Carbon::now()->subDays(2))->delete;
    }
}
