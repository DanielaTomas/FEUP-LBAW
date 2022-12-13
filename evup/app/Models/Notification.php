<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    protected $table = 'notification';

    protected $primaryKey = 'notificationid';

    public function receiver() 
    {
        return $this->belongsTo(User::class, 'receiverid');
    }

    public function event() 
    {
        return $this->belongsTo(Event::class, 'eventid');
    }

    public function poll() 
    {
        return $this->belongsTo(Poll::class, 'pollid');
    }

    public function join_request() 
    {
        return $this->belongsTo(JoinRequest::class ,'joinrequestid');
    }

    public function organizer_request() 
    {
        return $this->belongsTo(OrganizerRequest::class, 'organizerrequestid');
    }

    public function invitation()
    {
        return $this->belongsTo(Invitation::class, 'invitationid');
    }
}