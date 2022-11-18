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
    $events = EventController::getPublicEvents();
    return view('pages.home', ['events' => $events]);
  }
}
