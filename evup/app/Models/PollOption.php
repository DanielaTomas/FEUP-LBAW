<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    public $timestamps  = false;

    protected $table = 'PollOption';
    protected $primaryKey = 'pollOptionId';

    public function event()
    {
        return $this->belongsTo(Poll::class, 'pollId');
    }

    public function answers()
    {
        return $this->belongsToMany(User::class, 'Answer', 'userId', 'pollOptionId');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pollId');
    }
}