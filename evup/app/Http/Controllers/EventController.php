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

    $user = User::find(Auth::id());
    //$this->authorize('show',$event);
    return view('pages.event',[
      'event'=>$event, 'user'=>$user
    ]);
  }

  public function edit($id) 
  {
    $event = Event::find($id);

    if(is_null($event))
      return abort(404,'Event not found');

    $user = User::find(Auth::id());

    //$this->authorize('edit',$event);
    return view('pages.event.edit',[
      'event'=>$event, 'user'=>$user
    ]);
  }

}
