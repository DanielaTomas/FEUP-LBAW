<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps  = false;

    protected $table = 'category';
    protected $primaryKey = 'categoryid';
    

  public function eventCategories()
  {
    return $this->belongsToMany(Event::class, 'event_category', 'categoryid', 'eventid');
  }
}