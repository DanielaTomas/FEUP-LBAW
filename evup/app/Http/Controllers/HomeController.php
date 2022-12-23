<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
use App\Models\Category;
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
    
    $events = $tag->eventTags()->get();
    return response()->json(view('partials.content.publicEvents', ['events' => $events])->render()
  , 200);
  }

  public function filterCategory(Request $request)
  {
    $categoryid = $request->categoryid;
    $category = Category::find($categoryid);
    
    $events = $category->eventCategories()->get();
    return response()->json(view('partials.content.publicEvents', ['events' => $events])->render()
  , 200);
  }
  
}
