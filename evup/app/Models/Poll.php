<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public $timestamps  = false;

    protected $table = 'poll';
    protected $primaryKey = 'pollid';

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventid');
    }

    public function poll_options()
    {
        return $this->hasMany(PollOption::class, 'pollid');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pollid');
    }
}