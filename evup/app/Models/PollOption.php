<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    public $timestamps  = false;

    protected $table = 'polloption';
    protected $primaryKey = 'polloptionid';

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'pollid');
    }

    public function answers()
    {
        return $this->belongsToMany(User::class, 'answer', 'polloptionid', 'userid');
    }

    public function nanswers()
    {
        return $this->answers()->count();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pollid');
    }

    public function voted($id)
    {
        $users = $this->answers()->where('userid','=', $id)->count();
        return $users>0;
    }
}