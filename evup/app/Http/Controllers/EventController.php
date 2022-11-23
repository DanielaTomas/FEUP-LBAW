<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

  public function delete(Request $request, $id)
  {
    $event = Event::find($id);

    $this->authorize('delete', $event);
    $event->delete();

    return $event;
  }

  public function showForms()
  {

    $tags = TagController::getAllTags();
    $categories = CategoryController::getAllCategories();

    return view('pages.createEvent', ['categories' => $categories, 'tags' => $tags]);
  }

  public function createEvent(Request $request)
  {

    //$this->authorize('create', Event::class);

    $validator = Validator::make(
      $request->all(),
      [
        'name' => 'required|string|min:3|max:100',
        'eventAddress' => 'required|string|min:3|max:200',
        'description' => 'required|string|min:3|max:100',
        'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:4096',
        'starDate' => 'required|date',
        'endDate' => 'required|date',
      ]
    );
    /*
    if ($validator->fails()) {
      // Go back to form and refill it
      return redirect()->back()->withInput()->withErrors($errors);
    }*/

    $event = new Event;
    $event->eventname = $request->name;
    $event->eventaddress = $request->eventAddress;
    $event->description = $request->description;
    $event->eventphoto = $request->thumbnail;
    $event->startdate = $request->startDate;
    $event->enddate = $request->endDate;
    $event->public = true;
    $event->userid = Auth::id();
    $event->save();

    return redirect("/event/$event->eventid");
  }

}
