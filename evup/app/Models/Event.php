<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $table = 'event';
  protected $primaryKey = 'eventid';

  protected $guarded = [
    'isCancelled', 'public',
  ];

  protected $fillable = [
    'eventaddress','description','eventPhoto','startDate','endDate'
  ];

  public function eventTags()
  {
    return $this->belongsToMany(Tag::class,'event_tag','eventid','tagid');
  }

  public function eventCategories()
  {
    return $this->belongsToMany(Category::class, 'event_category', 'eventid', 'categoryid');
  }

  public function events()
  {
    return $this->belongsToMany(User::class, 'attendee', 'eventid', 'attendeeid');
  }

  public function comments()
  {
    return $this->hasMany(Comment::class, 'eventid');
  }

  public function polls()
  {
    return $this->hasMany(Poll::class, 'eventId');
  }

  public function organizer()
  {
    return $this->belongsTo(User::class,'userId');
  }

  public function reports()
  {
    return $this->hasMany(Report::class, 'reporterId');
  }

  public function notifications()
  {
    return $this->hasMany(Notification::class, 'eventId');
  }
}
