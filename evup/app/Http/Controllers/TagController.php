<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Tag;

class TagController extends Controller
{
 
  /**
   * Shows all tags.
   *
   * @return Response
   */
  static function getAllTags()
  {
    return Tag::get();
  }
}