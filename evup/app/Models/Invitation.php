<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public $timestamps = false;

    protected $table = 'invitation';
    protected $primaryKey = 'invitationid';
    

    public function sender() 
    {
        return $this->belongsTo(User::class, 'inviterid');
    }

    public function receiver() 
    {
        return $this->belongsTo(User::class, 'inviteeid');
    }

    public function event() 
    {
        return $this->belongsTo(Event::class, 'eventid');
    }

}