<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{

    public function send(Request $request, $id){
         
        $invite = new Invite();
        //$this->authorize('send', $id);
        $invite->eventid = $id;
        $invite->inviterId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->messages() as $key => $value) {
                $errors[$key] = is_array($value) ? implode(',', $value) : $value;
            }
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $userId = DB::table('Users')->where('username',$request->usename)->first();

        if(isset($request->username)) $invite->inviteeId = $userId;

        $invite->save();

        return redirect()->route('event.show', $id);
    }

}
