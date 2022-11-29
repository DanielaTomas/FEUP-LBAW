<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class PasswordResetController extends Controller
{   
   
    protected $redirectTo = '/';

    public function __construct() {
        $this->middleware('guest');
    }

    public function showSendLinkForm() {
        return view('auth.forgotPassword');
    }

    public function sendLink(Request $request) {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response === Password::RESET_LINK_SENT
            ?   back()->with(['status' => __($response)])
            :   back()->withErrors(['email' => __($response)]);
    }

    public function showResetPasswordForm(Request $request, $token = null) {
        return view('auth.resetPassword')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request) {
        $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
    ]);

        $response = Password::reset([
            'token' => $request->token,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return $response === Password::PASSWORD_RESET
            ?   back()->with(['status' => __($response)])
            :   back()->withErrors(['password' => __($response)]);
    }
 
}