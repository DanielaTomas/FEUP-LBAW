<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{

  /**
   * Shows all events.
   *
   * @return Response
   */
  static function getPublicEvents()
  {
    return Event::where('public','=',true)->get();
  }

  public static function searchPublicEvents(Request $request)
  {
    $search = $request->input('search');
    $events = Event::whereRaw('tsvectors @@ to_tsquery(\'english\', ?)', [$search])
      ->where('public','=',true)->get();
    return $events;
  }

  public function show($id) 
  {
    $event = Event::find($id);

    if(is_null($event))
      return abort(404,'Event not found');

    //$this->authorize('show',$event);
    return view('pages.event',[
      'event'=>$event,
    ]);
  }

  public function userEvents()
  {
    $this->authorize('list', Event::class);
    $myEvents = Auth::user()->events()->get();
    
    return view('pages.myEvents', ['events' => $myEvents]);
  }

  public function organizerEvents()
  {
    $organizer = User::find(Auth::id());
    if (is_null($organizer))
      return abort(404, 'User not found');
    $this->authorize('organizerEvents', $organizer);
    $events = Event::where('userid', $organizer->userid)->get();

    return view('pages.organizerEvents', ['events' => $events]);
  }

  public function attendees(Request $request,$id)
  {
    $organizer = User::find(Auth::id());
    if (is_null($organizer))
      return abort(404, 'User not found');
    $event = Event::find($id);
    if (is_null($event))
      return abort(404, 'Event not found');
    $this->authorize('attendees', $event);

    $attendees = DB::table('attendee')
            ->select('attendeeid', 'eventid')
            ->where('eventid', $id)
            ->get()
            ->map(function ($attendee) {

      $user = User::find($attendee->attendeeid);
      $event = Event::find($attendee->eventid);

      return [
          'user' => $user,
          'event' => $event,
      ];
   });
    
    return view('pages.attendees', ['attendees' => $attendees]);
  }

  public function view_add_user($id)
  {
    $organizer = User::find(Auth::id());
    if (is_null($organizer))
      return abort(404, 'User not found');
    $event = Event::find($id);
    if (is_null($event))
      return abort(404, 'Event not found');
    $this->authorize('view_add_user', $event);

    $usersInvited = Auth::user()->invites_sent()->get();
    $usersAttending = Event::find($id)->events()->get();
    
    $users = User::get();

    $usersAttending->push(Auth::user());

    $users = $users->diff($usersInvited);
    $users = $users->diff($usersAttending);

    return view('pages.add_users', ['users' => $users]);
  }

  public function addUser(Request $request, int $userid, int $eventid)
  {  
    $organizer = User::find(Auth::id());
    if (is_null($organizer))
        return abort(404, 'User not found');
    $user = User::find($userid);
    if (is_null($user))
        return response()->json([
            'status' => 'Not Found',
            'msg' => 'User not found, id: '.$userid,
            'errors' => ['user' => 'User not found, id: '.$userid]
        ], 404);

    $event = User::find($eventid);
    if (is_null($event))
        return response()->json([
            'status' => 'Not Found',
            'msg' => 'Event not found, id: '.$eventid,
            'errors' => ['event' => 'Event not found, id: '.$eventid]
        ], 404);

    $this->authorize('addUser', $organizer);

    $event->events()->attach($userid);

    return response()->json([
        'status' => 'OK',
        'msg' => 'Successfully added user '.$user->name .'to event'.$event->eventname,
    ], 200);
  }

  public function removeUser(Request $request, int $userid, int $eventid)
  {  
    $organizer = User::find(Auth::id());
    if (is_null($organizer))
        return abort(404, 'User not found');
    $user = User::find($userid);
    if (is_null($user))
        return response()->json([
            'status' => 'Not Found',
            'msg' => 'User not found, id: '.$userid,
            'errors' => ['user' => 'User not found, id: '.$userid]
        ], 404);

    $event = User::find($eventid);
    if (is_null($event))
        return response()->json([
            'status' => 'Not Found',
            'msg' => 'Event not found, id: '.$eventid,
            'errors' => ['event' => 'Event not found, id: '.$eventid]
        ], 404);

    $this->authorize('removeUser', $organizer);

    $event->events()->detach($userid);

    return response()->json([
        'status' => 'OK',
        'msg' => 'Successfully removed user '.$user->name .'from event'.$event->eventname,
    ], 200);
  }

  public function delete(Request $request, $id)
  {
    $event = Event::find($id);

    $this->authorize('delete', $event);
    $event->delete();

    return $event;
  }

  public function showForms(){

    $tags = TagController::getAllTags();
    $categories = CategoryController::getAllCategories();

    return view('pages.createEvent', ['categories' => $categories, 'tags' => $tags ]);
  }

}
