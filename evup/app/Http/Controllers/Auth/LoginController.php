<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;


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

    //Google Login
    public function redirectToGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
        }
        
    //Google callback  
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();

        $appUser = User::where('email', $user->email)->first();

        if ($appUser) {
            Auth::login($appUser);
            return redirect('/');
        } else {

            $newUser = User::create([
                'name' => $user->name,
                'username' => $user->name,
                'email' => $user->email,
                'userphoto' => 1,
                'password' => encrypt($user->id),
                'accountstatus'=> 'Active',
                'usertype'=> 'User'
            ]);

            Auth::login($newUser);
            return redirect('/');
        }

    }
}