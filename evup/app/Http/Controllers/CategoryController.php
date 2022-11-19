<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
 
  /**
   * Shows all categories.
   *
   * @return Response
   */
  static function getAllCategories()
  {
    return Category::get();
  }
}