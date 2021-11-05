<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvitationLink;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    public function invite(Request $request){

        $rules =  [
            'email' => 'required|string|email|unique:users',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->errors()->count()) {
            return response()->json($validation->messages()->all(), 422);
        }

        $token = Str::random(16);
        $token = $token.''.time();

        InvitationLink::create([
            'email' => $request->email,
            'token' => $token
        ]);

        Mail::send(['html' => 'signUpInvitation'], ['token' => $token], function ($message) use ($request) {
            $message->from('test@mail.com', 'Test');
            $message->to($request->email)->subject('Signup Invitation');
        });
    }
}
