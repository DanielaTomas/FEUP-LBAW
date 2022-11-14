<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'event';

  protected $primaryKey = 'event_id';

  
  public function eventTags() {
    return $this->belongsToMany(Tag::class, 'event_tag', 'event_id', 'tag_id');
  }

  public function eventCategories() {
    return $this->belongsToMany(Tag::class, 'event_category', 'event_id', 'category_id');
  }

  public function comments() {
    return $this->hasMany(Comment::class, 'event_id');
  }

  public function polls() {
    return $this->hasMany(Poll::class, 'event_id');
  }

  public function organizer() {
    return $this->belongsTo(User::class);
  }

  public function eventAtendees() {
    return $this->belongsToMany(User::class, 'user_event', 'user_id', 'event_id');
  }
}