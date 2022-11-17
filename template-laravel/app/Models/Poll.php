<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public $timestamps  = false;

    protected $table = 'Poll';
    protected $primaryKey = 'pollId';

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    public function poll_options()
    {
        return $this->hasMany(PollOption::class, 'pollId');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pollId');
    }
}