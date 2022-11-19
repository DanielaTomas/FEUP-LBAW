<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invitition;
use App\Models\Event;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{

     /**
     * Send invitation
     * 
     * @param  Illuminate\Http\Request  $request
     * @param int $id event id
     * @return \Illuminate\Http\Response
    */
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

        return redirect()->route('pages.event.show', $id);
    }


    /**
   * Page with information about all the invitations
   * 
   * @return View
   */
    public function invitations() {
         
        $this->authorize('invitations', User::class);
        
        $invitationsInfo = Invitation::orderByDesc('invitationId')->get()
          ->map(function ($invitation) {

                $user = User::find($invitation->inviterId);
                $inviterInfo = [
                    'id' => $invitation->eventId,
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'userPhoto' => $user->userPhoto,
                    'accountStatus' => $user->accountStatus,
                    'userType' => $user->userType,
                ];

                $event = Event::find($invitation->eventId);
                $eventInfo = [
                    'id' => $invitation->eventId,
                    'eventName' => $event->eventName,
                    'public' => $event->public,
                    'address' => $event->address,
                    'description' => $event->description,
                    'eventPhoto' => $event->eventPhoto,
                ];

                return [
                    'id' => $invation->id,
                    'inviter' => $inviterInfo,
                    'event' => $eventInfo,
                ];
          });

        return view('pages.user.invitations', ['invitation' => $invitationInfo]);
    }

}
