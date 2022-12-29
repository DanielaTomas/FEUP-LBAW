<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appeal;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UnbanAppealMail;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{
    /**
     * Displays the unban appeal page
     * 
     * @return View
     */
    public function getAppeal(int $userid)
    {
        $user = User::find($userid);
        if (is_null($user))
            return abort(404, 'User not found');

        if (Auth::check())
            return abort(403, 'Forbidden Access');

        if ($user->accountstatus != 'Blocked')
            return abort(403, 'Forbidden Access');

        return view('auth.unban', [
            'userid' => $userid
        ]);
    }


    public function saveAppeal(Request $request, int $id) { 
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found');

        if (Auth::check())
            return abort(403, 'Forbidden Access');

        if ($user->accountstatus != 'Blocked')
            return abort(403, 'Forbidden Access');

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $appeal = new Appeal;

        $appeal->userid = $id;
        $appeal->name = $request->name;
        $appeal->email = $request->email;
        $appeal->message = $request->message;

        $appeal->save();

        Mail::to('admin@evup.com')->send(new UnbanAppealMail(array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'user_message' => $request->get('message'),
        )));
        
        return back()->with('success', 'Thank you for your appeal. We will review it as soon as possible.');
    }
}