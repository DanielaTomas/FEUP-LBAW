<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    public $timestamps  = false;

    protected $table = 'polloption';
    protected $primaryKey = 'polloptionid';

    public function event()
    {
        return $this->belongsTo(Poll::class, 'pollid');
    }

    public function answers()
    {
        return $this->belongsToMany(User::class, 'answer', 'userid', 'polloptionid');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pollid');
    }
}