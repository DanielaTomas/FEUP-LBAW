<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

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
  
}
