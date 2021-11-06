<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;

class checkOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      if(Auth::user()){
        $userVerified = Auth::user()->where('otp_verified',1)->first();

        if(!$userVerified){
          return response()->json(["message"=>"please verify your email"],422);
        }

      }else{
        //for login route
        $user = User::where('email',$request->email)->where('otp_verified',1)->first();

        if(!$user){
          return response()->json(["message"=>"please verify your email"],422);
        }
      }
        return $next($request);
    }
}
