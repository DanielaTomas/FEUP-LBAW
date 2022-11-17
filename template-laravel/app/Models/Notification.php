<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    protected $table = 'Notification';

    protected $primaryKey = 'notificationId';

    public function receiver() 
    {
        return $this->belongsTo(User::class, 'receiverId');
    }

    public function event() 
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    public function poll() 
    {
        return $this->belongsTo(Poll::class, 'pollId');
    }

    public function join_request() 
    {
        return $this->belongsTo(JoinRequest::class ,'joinRequestId');
    }

    public function organizer_request() 
    {
        return $this->belongsTo(OrganizerRequest::class, 'organizerRequestId');
    }

    public function invitation()
    {
        return $this->belongsTo(Invitation::class, 'invitationId');
    }
}