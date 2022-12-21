<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
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
        'username', 'name', 'email', 'password', 'userPhoto', 'usertype', 'accountstatus'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
    protected $nullable = ['userphoto', 'remember_token'];

    /* Delete info deletes every info associated with the deleted user: Invites sent and receveied; Notifications, */
    /* Organizer Requests issued by the user, Join Requests issued by the user */
    /* Nullable fields are set to NULL and the rest is filled with dummy text ('deleteduser{id}') */
    public function delete_info() {
        foreach($this->nullable as $field) {
            $this->{$field} = null;
        }

        $this->username = 'deleteduser' . $this->userid;
        $this->name = 'Deleted User';
        $this->email = 'deleteduser' . $this->userid . '@evup.com';
        $this->password = 'deleteduser' . $this->userid;
        $this->accountstatus = 'Disabled';

        $this->save();

        /* Delete all invites sent by the user */
        DB::table('invitation')->where('inviterid', $this->userid)->delete();
        /* Delete all invites received by the user */
        DB::table('invitation')->where('inviteeid', $this->userid)->delete();
        /* Delete all organizer requests issued by the user */
        DB::table('organizerrequest')->where('requesterid', $this->userid)->delete();
        /* Delete all join requests issued by the user */
        DB::table('joinrequest')->where('requesterid', $this->userid)->delete();
        /* Delete all join requests issued by the user */
        DB::table('notification')->where('receiverid', $this->userid)->delete();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiverid');

    }

    public function unreadnotifications()
    {
        return $this->notifications->where('notificationstatus', '=','false');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'attendee', 'attendeeid', 'eventid');
    }

    public function isAttendee($event) {
        return $this->events->contains($event);
    }

    public function createdEvents() {
        return $this->hasMany(Event::class, "userid");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'authorid');
    }

    public function requests()
    {
        return $this->hasMany(OrganizerRequest::class, 'requesterid');
    }

    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class, 'requesterid');
    }

    public function votes()
    {
        return $this->belongsToMany(Comment::class, 'vote', 'commentid', 'voterid')->withPivot('type');
    }

    public function answers()
    {
        return $this->belongsToMany(PollOption::class, 'answer', 'userid', 'polloptionid');
    }
    
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporterid');
    }

    public function invites_sent()
    {
        return $this->belongsToMany(User::class, 'invitation', 'inviterid', 'inviteeid')->withPivot('eventid');
    }

    public function invites_received()
    {
        return $this->hasMany(Invitation::class, 'inviteeid');
    }

    public function ordered_events()
    {
        return $this->events->map(function ($area) {
            return [
                'eventid' => $area->eventid,
                'eventname' => $area->name,
                'enddate' => $area->enddate,
            ];
        })->sortBy('enddate')->where('enddate', '<', date("Y-m-d H:i:s"));
    }

    public function ordered_invites()
    {
        return $this->invites_received->map(function ($area) {
            return [
                'invitationid' => $area->invitationid,
                'inviterid' => $area->inviterid,
                'eventid' => $area->eventid,
                'invitationstatus' => $area->invitationstatus,
            ];
        })->sortBy('eventid')->where('invitationstatus', '!=', TRUE);
    }


    public function isAttending($eventId)
    {
        $attendeeList = $this->events->where('eventid', $eventId);
        return count($attendeeList) > 0;
    }

    public function hasInvited($invitedUserId,$eventId)
    {
        $invited = $this->invites_sent()->where('inviteeid','=', $invitedUserId)->where('eventid','=', $eventId)->get()->count();
        return $invited > 0;
    }

    public function hasRequest(){
        
        $req = $this->hasMany(OrganizerRequest::class, 'requesterid')->where('requeststatus', '=', NULL)->count();
        if($req==0){
            return false;
        }
        return true;
    }
}
