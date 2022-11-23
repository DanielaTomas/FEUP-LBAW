<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $admin = User::find(Auth::id());
        if (is_null($admin))
            return abort(404, 'User not found');
        $this->authorize('searchUsers', $admin);

        $search = $request->input('search');
        $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$search])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$search])
            ->get();

        return view('pages.admin.users',[
            'users' => $users,
        ]);
    }


    public function searchNonAttendingUsers(Request $request){
        $search = $request->input('search');
        $eventid = $request->input('eventid');

        $usersInvited = Auth::user()->invites_sent()->get();
        $usersAttending = Event::find($eventid)->events()->get();
        
        $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$search])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$search])
            ->get();

        $usersAttending-> push(Auth::user());

        $users = $users->diff($usersInvited);
        $users = $users->diff($usersAttending);

        return $users;
    }

    public function searchPublicEvents(Request $request)
    {
        $search = $request->input('search');
        $events = Event::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$search])
            ->where('public','=',true)
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$search])
            ->get();

        return $events;
    }
}