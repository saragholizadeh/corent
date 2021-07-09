<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Mail\VerifyMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EmailVerifyController extends Controller
{

    public function sendNotification(){

    $random = $this->generateRandomString(6);
    
            $details = [
                'title' => 'Mail from ItSolutionStuff.com',
                'body' =>$random,
            ];

            Mail::to('calikeax@gmail.com')->send(new VerifyMail($details));

        return response()->json([
            'message'=>'your email verification code sent to your email'
        ] , 201);
    }

    public function generateRandomString($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function emailVerify(){

    }

}
