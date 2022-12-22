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
    'eventaddress','description','eventphoto','startdate','enddate'
  ];

  public function photo()
  {
      return $this->hasOne(Upload::class, 'uploadid');
  }

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

  public function hasAttendee($user) {
    return $this->events->contains($user);
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

  public function getDate()
  {
    $startmonth = date('M', strtotime( $this['startdate'] ) );
    $startday = date('d', strtotime( $this['startdate'] ) );
    $starthour = date('H:i', strtotime( $this['startdate'] ) );
    $endmonth = date('M', strtotime( $this['enddate'] ) );
    $endday = date('d', strtotime( $this['enddate'] ) );
    $endhour = date('H:i', strtotime( $this['enddate'] ) );

    return [
      'startmonth' => $startmonth,
      'startday' => $startday,
      'starthour' => $starthour,
      'endmonth' => $endmonth,
      'endday' => $endday,
      'endhour' => $endhour,
    ];
  }
}
