<?php

namespace App\Http\Controllers\Api\Admin;


use App\Models\Token;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Traits\EmailVerifyTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use EmailVerifyTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){

    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['error' => 'احراز نشده'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'=>'required|string|min:3|max:30',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)],
                ));

        $token = JWTAuth::fromUser($user);

        $activationCode = $this->generateVerificationCode();

        $newToken = new Token;
        $newToken->code = $activationCode;
        $newToken->status = 0;
        $newToken->user_id = $user->id;
        $newToken->expires_in = Carbon::now()->addMinutes(10);
        $newToken->new_code_date = Carbon::now()->addMinutes(2);
        $newToken->save();

        $details = [
            'greeting' => 'سلام'.$request->name,
            'body' => 'لطفا از کد زیر برای تایید ایمیل خود و فعالسازی حساب کاربری خود استفاده کنید',
            'activation_code'=>$newToken->code,
            'thanks' => 'متشکریم',
            'order_id' => 101
        ];

        Notification::send($user, new EmailVerification($details));


        return response()->json([
            'message' => 'کاربر با موفقیت ثبت شد و کد فعالسازی اکانت ارسال شد',
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {

        auth()->logout();
        return response()->json(['message' => 'با موفقیت خارج شد']);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 500,

            'user' => auth()->user()
        ]);
    }

}
