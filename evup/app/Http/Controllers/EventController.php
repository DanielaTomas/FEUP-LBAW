<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;

class EventController extends Controller
{
 
  /**
   * Shows all cards.
   *
   * @return Response
   */
  static function getPublicEvents()
  {

    return Event::get();
    
  }
}