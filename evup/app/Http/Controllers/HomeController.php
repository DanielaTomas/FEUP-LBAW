<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
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
    /*
    if(in_array($tagid,$this->tagids)){
      $key = array_search($tagid, $this->tagids);
      unset($this->tagids[$key]);
      array_values($this->tagids);
    } else{
      array_push($this->tagids, $tagid);
    }*/
    
    //$events = [];
    //foreach($this->tagids as $x){
      $tag = Tag::find($tagid);
      //return response()->json(['msg' => 'Successfully added user '.$tag->tagname], 200);
      $tagEvents = $tag->eventTags()->get();
     
      //array_merge($events, $tagEvents);
    //}
    //return response()->json(['msg' => 'Successfully added user '.count($tagEvents)], 200);
    return $tagEvents;
  }
  
}
