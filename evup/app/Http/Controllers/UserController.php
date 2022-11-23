<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\Report;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function leaveEvent($eventid)
    {
        $event = Event::find($eventid);

        if (is_null($event))
            return response()->json([
                "'status' => 'Not Found',
                'msg' => 'Event not found, id: ' . $eventid,
                'errors' => ['user' => 'User not found, id: ' . $eventid]"
            ], 404);

        $this->authorize('leaveEvent', User::class);

        if (!Auth::user()->isAttending($eventid))
            return response()->json([
                'status' => 'OK',
                'msg' => 'User is not attending event',
                'id' => $eventid,
            ], 200);

        Auth::user()->events()->detach($eventid);

        return response()->json([
            'status' => 'OK',
            'msg' => 'Removed event successfully ',
            'id' => $eventid,
        ], 200);
    }

    public function searchUsers(Request $request){
        $event = Event::find($request->eventid);
        $organizer = User::find($event->userid);

        $usersInvited = Auth::user()->invites_sent()->get();
        $usersAttending = $event->events()->get();
        
        $users = User::whereRaw('(username like \'%' . $request->search . '%\' or email like \'%' . $request->search . '%\')')
                    ->get();

        $usersAttending-> push(Auth::user());
        $usersAttending-> push($organizer);

        $users = $users->diff($usersInvited);
        $users = $users->diff($usersAttending);

        return $users;
    }

    public function inviteUser(Request $request)
    {
        $inviteddUser = User::where('email', $request->email)->first();
       
        if (is_null($inviteddUser))
            return response()->json([
                'status' => '404',
                'msg' => 'User not found, User'. $invitedUserEmail,
                'errors' => ['user' => 'User not found']
            ], 404);

        $inviteddUserId = $inviteddUser->userid;
        $this->authorize('invite', $inviteddUser);

        if (Auth::user()->hasInvited($inviteddUserId, $request->eventid))
            return response()->json([
                'status' => '400',
                'msg' => 'User already invited',
                'id' => $inviteddUserId,
            ], 400);

        Auth::user()->invites_sent()->attach($inviteddUserId,['eventid' => $request->eventid]);

        return response()->json([
            'status' => '200',
            'msg' => 'Invited user successfully',
            'id' =>$request->eventid,
        ], 200);
    }

    /**
     * Display the User profile.
     *
     * @return View
     */
    public function show()
    {
        $user = User::find(Auth::id());
        if (is_null($user))
            return abort(404, 'User not found, id: ' . Auth::id());

        $ordered_events = $user->ordered_events();
        $ordered_invites = $user->ordered_invites();
        $isOrganizer = 'Organizer' == $user->userType;

        return view('pages.user.profile', [
            'user' => $user,
            'events' => $ordered_events,
            'invites' => $ordered_invites,
            'isOrganizer' => $isOrganizer,
        ]);
    }

    /**
     * Show the form for editing the user profile.
     *
     * @param  int $id Id of the user
     * @return View
     */
    public function profile(int $userid)
    {
        $user = User::find($userid);
        if (is_null($user))
            return abort(404, 'User not found, id: ' . $userid);

        $userInfo = [
            'id' => $userid,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'userPhoto' => $user->userPhoto,
            'accountStatus' => $user->accountStatus,
            'userType' => $user->userType,
        ];

        $isOrganizer = false;
        if (Auth::check()) {
            $isOrganizer = Auth::id() == $userInfo['id'];
        }


        $ordered_events = $user->events()->get();
        $ordered_invites = $user->invites_received()->get();


        return view('pages.profile', [
            'user' => $userInfo,
            'events' => $ordered_events,
            'invites' => $ordered_invites,
            'isOrganizer' => $isOrganizer,
        ]);
    }

    public function showEditForms(){

        $user = Auth::user();
        if (is_null($user))
            return abort(404, 'User not found, id: ' . $user->userid);

        return view('pages.editProfile', [
            'user' => $user
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id Id of the user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->authorize('update', User::class);

        $user = Auth::user();
        $user->name = $request->name;

        $repeatedUsername = User::where('username',$user->username)->first();
        $repeatedEmail = User::where('email',$user->email)->first();

        if (isset($request->username) && $repeatedUsername->id != Auth::id()){
            $user->username = $request->username;
        }else{
            return redirect()->back()->withInput();
        } 
        if (isset($request->email) && $repeatedEmail->id != Auth::id()){
            $user->email = $request->email;
        }else{
            return redirect()->back()->withInput();
        }

        $user->save();
        return redirect("/user/$user->userid");
    }

    /**
     * Deletes a user account.
     *
     * @param  Illuminate\Http\Request  $request
     * @param int $id Id of the user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, int $id): RedirectResponse
    {
        $user = User::find($id);
        if (is_null($user))
            return redirect()->back()->withErrors(['user' => 'User not found, id: ' . $id]);

        $this->authorize('delete', $user);

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|password'
        ]);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator->errors());

        $deleted = $user->delete();
        if ($deleted)
            return redirect('/');
        else
            return redirect()->back()->withErrors(['user' => 'Failed to delete user account. Try again later']);
    }
}
