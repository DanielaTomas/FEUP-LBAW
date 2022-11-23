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
    'address','description','eventPhoto','startDate','endDate'
  ];

  public function eventTags()
  {
    return $this->belongsToMany(Tag::class,'event_tag','eventid','tagid');
  }

  public function eventCategories()
  {
    return $this->belongsToMany(Category::class, 'event_category', 'eventid', 'categoryid');
  }

  public function attendees()
  {
    return $this->belongsToMany(User::class, 'attendee', 'eventid', 'attendeeid');
  }

  public function comments()
  {
    return $this->hasMany(Comment::class, 'eventid');
  }

  public function polls()
  {
    return $this->hasMany(Poll::class, 'eventid');
  }

  public function organizer()
  {
    return $this->belongsTo(User::class,'userid');
  }

  public function reports()
  {
    return $this->hasMany(Report::class, 'reporterid');
  }

  public function notifications()
  {
    return $this->hasMany(Notification::class, 'eventid');
  }
}
