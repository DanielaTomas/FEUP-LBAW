<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getUser(Request $request){
        return $request->user();
    }

    public function home() {
        return redirect('login');
    }
    
    protected function authenticated(Request $request, $user)
    {
        if ($user->accountstatus == 'Blocked') {
            $userid = Auth::id();
            Auth::logout();

            return back()->with([
                'ban' => 'Your account has been banned by an administrator. To create an unban appeal, please click ',
                'userid' => $userid,
            ]);
        }
        else if ($user->accountstatus == 'Disabled') {
            Auth::logout();

            return back()->withErrors([
                'deleted' => 'This account has been deleted.',
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }
}
