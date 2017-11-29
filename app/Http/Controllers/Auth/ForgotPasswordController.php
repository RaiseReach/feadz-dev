<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(Request $request)
    {
        if ($request->ajax()) 
        {
            $this->validate($request, ['email' => 'required|email']);
            $user = \App\User::where('email', $request->email);
            if(!$user) 
            {
                return response()->json(['user' => 'User not found with such email address.'], 422);
            }
            $response = $this->broker()->sendResetLink(
                $request->only('email'), function($token) {
                    return new ResetPasswordNotification($token);
                } 
            );
            return response()->json(['success' => true], 200);
        }
    }
}
