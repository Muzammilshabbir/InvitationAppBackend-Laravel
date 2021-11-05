<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InvitationLink;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Auth;

class AuthController extends Controller
{

    public function register(Request $request,$token)
    {
        $link = InvitationLink::where('token',$token)->first();

        if(!$link){
            return response()->json(['message'=>'invalid link'], 422);
        }

        $request['email'] = $link->email;

        $rules =  [
            'user_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->errors()->count()) {
            return response()->json($validation->messages()->all(), 422);
        }

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // $link->delete();

        $otp = $this->generateOtp(5);

        Otp::create([
            'user_id' => $user->id,
            'pin' => $otp,
        ]);

        Mail::send(['html'=>'otp'], ['otp'=>$otp], function ($message) use ($request) {

            $message->from("register@mail.com");
            $message->to($request->email)->subject("OTP");
        });

        $token = $user->createToken('TodoManager-Api')->plainTextToken;

        $response = [
            'token'=>$token,
            'type' => 'bearer',
            'user' => $user
        ];

        return response($response, 201);
    }

    protected function generateOtp($digits) {

        $min = pow(10, $digits - 1);
        $max = pow(10, $digits) - 1;
        return mt_rand($min, $max);
    }

    public function confirmPin(Request $request){

        $rules =  [
            'pin' => 'required|string|exists:otps,pin',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->errors()->count()) {
            return response()->json($validation->messages()->all(), 422);
        }

        $otp = Otp::where('pin',$request->pin)
        ->where('user_id',Auth::id())
        ->first();

        if($otp){

            if(!$otp->user){
                return response()->json(['message'=>'User does not exist'],200);
            }

            $otp->user->update([
                'otp_verified' => 1
            ]);

            return response()->json(['message'=>'Pin Verified Successfully'],200);
        }

        return response()->json(['message'=>'Invalid Pin'],422);
    }


    public function updateProfile(){
        $rules =  [
            'user_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->errors()->count()) {
            return response()->json($validation->messages()->all(), 422);
        }
    }
}
