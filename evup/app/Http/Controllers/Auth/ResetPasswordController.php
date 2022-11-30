<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{   
    use ResetsPasswords;
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

    public function showResetPasswordForm(Request $request)
    {
        $token = $request->route()->parameter('token');

        return view('auth.resetPassword')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
 
}