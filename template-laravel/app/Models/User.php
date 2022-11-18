<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $primaryKey = 'userid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','userPhoto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function cards() {
        return $this->hasMany(Card::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'Attendee', 'attendeeId', 'eventId');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'authorId');
    }

    public function votes()
    {
        return $this->belongsToMany(Comment::class, 'Vote', 'voterId', 'commentId')->withPivot('type');
    }

    public function polls_options()
    {
        return $this->belongsToMany(PollOption::class, 'Answer', 'userId', 'pollOptionId');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporterId');
    }

    public function invites_sent()
    {
        return $this->hasMany(User::class, 'inviterId');
    }

    public function invites_receives()
    {
        return $this->hasMany(User::class, 'inviteeId');
    }

}
