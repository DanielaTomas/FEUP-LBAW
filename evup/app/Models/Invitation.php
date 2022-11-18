<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public $timestamps = false;

    protected $table = 'Invitation';
    protected $primaryKey = 'invitationID';
    
    public function sender() 
    {
        return $this->belongsTo(User::class, 'inviterId');
    }

    public function receiver() 
    {
        return $this->belongsTo(User::class, 'inviteeId');
    }

    public function event() 
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

}