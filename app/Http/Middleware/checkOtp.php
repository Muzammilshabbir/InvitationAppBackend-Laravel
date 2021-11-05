<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

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
      $userVerified = Auth::user()->where('otp_verified',1)->first();

      if(!$userVerified){
            return response()->json(["message"=>"please verify your email"],422);
      }
        return $next($request);
    }
}
