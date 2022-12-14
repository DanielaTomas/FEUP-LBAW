<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

  private $tagids= [];
  /**
   * Shows all public events.
   *
   * @return Response
   */
  public function list()
  {
    $tags = TagController::getAllTags();
    $events = EventController::getPublicEvents();
    $categories = CategoryController::getAllCategories();
    if (Auth::check()) {
      $notificationsController = new NotificationController();
      $notifications = $notificationsController->getAllNotifications();
      return view('pages.home', ['events' => $events, 'tags' => $tags, 'categories' => $categories, 'notifications' => $notifications]);
    }
    else
      return view('pages.home', ['events' => $events, 'tags' => $tags, 'categories' => $categories]);
  }

  public function searchEvents(Request $request)
  {
    $categories = CategoryController::getAllCategories();
    $tags = TagController::getAllTags();
    $events =  EventController::searchPublicEvents($request);
    return view('pages.home', ['events' => $events, 'tags' => $tags, 'categories' => $categories]);
  }

  public function filterTag(Request $request)
  {
    $tagid = $request->tagid;
    $tag = Tag::find($tagid);
    /*
    if(in_array($tagid,$this->tagids)){
      $key = array_search($tagid, $this->tagids);
      unset($this->tagids[$key]);
      array_values($this->tagids);
    } else{
   
      array_push($this->tagids, $tagid);
    }

    $events= collect();
    foreach($this->tagids as $x){
      $tag = Tag::find($x);
      $tagEvents = $tag->eventTags()->get();
 
      $events = $events->merge($tagEvents);
    }
    */
    //return response()->json(['msg' => 'Successfully added user '.count($events)], 200);
    $events = $tag->eventTags()->get();
    return response()->json(view('partials.content.publicEvents', ['events' => $events])->render()
  , 200);
  }
  
}
