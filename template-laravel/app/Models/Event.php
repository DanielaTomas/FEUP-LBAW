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
    return $this->belongsToMany(Tag::class);
  }

  public function eventCategories()
  {
    return $this->belongsToMany(Category::class, 'CategoryEvent', 'eventId', 'categoryId');
  }

  public function comments()
  {
    return $this->hasMany(Comment::class, 'eventId');
  }

  public function polls()
  {
    return $this->hasMany(Poll::class, 'eventId');
  }

  public function organizer()
  {
    return $this->belongsTo(User::class,'organizerId');
  }

  public function eventAtendees()
  {
    return $this->belongsToMany(User::class, 'Attendee','eventId','attendeeId');
  }
}
