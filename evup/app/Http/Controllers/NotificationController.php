<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Event;
use App\Models\Invitation;
use App\Models\JoinRequest;
use App\Models\OrganizerRequest;
use App\Models\User;
use App\Models\Poll;
use DateTime;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Returns a json with a view of the list of the user's notifications
     * 
     * @return JSON
     */
    public function show()
    {
        $this->authorize('notifications', User::class);

        $notifications = Auth::user()->notifications->sortByDesc('notificationdate')
            ->where('notificationstatus', '=', FALSE)
            ->map(function ($notification) {
                $now = new DateTime('now');
                $notifDate = new DateTime($notification->notificationdate);
                $interval = $now->diff($notifDate);
                $timeDiff = $this->formatTimeDiff($interval);

                $info = [
                    'notificationid' => $notification->notificationid,
                    'notificationtype' => $notification->notificationtype,
                    'notificationstatus' => $notification->notificationstatus,
                    'time' => $timeDiff,
                ];

                if ($notification->notificationtype === "EventChange")
                {
                    $event = Event::find($notification->eventid);

                    $info['eventid'] = $event->eventid;
                    $info['eventname'] = $event->eventname;
                    $info['eventphoto'] = $event->eventphoto;

                } 
                else if ($notification->notificationtype === "JoinRequestReviewed")
                {
                    $joinrequest = JoinRequest::find($notification->joinrequestid);
                    $event = Event::find($joinrequest->eventid);

                    $info['eventid'] = $event->eventid;
                    $info['eventname'] = $event->eventname;
                    $info['eventphoto'] = $event->eventphoto;
                    $info['requeststatus'] = $joinrequest->requeststatus;
                }
                else if ($notification->notificationtype === "OrganizerRequestReviewed")
                {
                    $organizerrequest = OrganizerRequest::find($notification->organizerrequestid);
                    $info['requeststatus'] = $organizerrequest->requeststatus;
                }
                else if ($notification->notificationtype === "InviteReceived")
                {
                    $invite = Invitation::find($notification->invitationid);
                    $user = User::find($invite->inviterid);
                    $event = Event::find($invite->eventid);

                    $info['userid'] = $user->userid;
                    $info['name'] = $user->name;
                    $info['userphoto'] = $user->userphoto;
                    $info['eventid'] = $event->eventid;
                    $info['eventname'] = $event->eventname;
                }
                else if ($notification->notificationtype === "InviteAccepted")
                {
                    $invite = Invitation::find($notification->invitationid);
                    $user = User::find($invite->inviteeid);
                    $event = Event::find($invite->eventid);

                    $info['userid'] = $user->userid;
                    $info['name'] = $user->name;
                    $info['userphoto'] = $user->userphoto;
                    $info['eventid'] = $event->eventid;
                    $info['eventname'] = $event->eventname;
                }
                else if ($notification->notificationtype === "NewPoll")
                {
                    $poll = Poll::find($notification->pollid);
                    $event = Event::find($poll->eventid);

                    $info['eventid'] = $event->eventid;
                    $info['eventname'] = $event->eventname;
                    $info['eventphoto'] = $event->eventphoto;
                    $info['pollid'] = $poll->pollid;
                }

                return $info;
            });

        return response()->json(view('partials.getNotifications', ['notifications' => $notifications])->render(), 200);
    }

    /**
     * Marks a single user notifications as read
     * 
     * @return Response
     */
    public function readNotification(int $id)
    {
        $this->authorize('notifications', User::class);

        $notification = Notification::find($id);
        $notification->notificationstatus = true;
        $notification->save();

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successfully marked notification as read'
        ], 200);
    }

    /**
     * Marks all user's notifications as read
     * 
     * @return Response
     */
    public function readNotifications()
    {
        $this->authorize('notifications', User::class);

        $notifications = Auth::user()->notifications;
        $notifications->each(function ($notification) {
            $notification->notificationstatus = true;
            $notification->save();
        });

        return response()->json([
            'status' => 'OK',
            'msg' => 'Successfully marked all notifications as read'
        ], 200);
    }

    private function formatTimeDiff($diff)
    {
        $res = $diff->format('%y years ago');
        if ($res[0] === '1')
            $res = $diff->format('%y year ago');
        if ($res[0] > '0') return $res;

        $res = $diff->format('%m months ago');
        if ($res[0] === '1')
            $res = $diff->format('%m month ago');
        if ($res[0] > '0') return $res;

        $res = $diff->format('%d days ago');
        if ($res[0] === '1')
            $res = $diff->format('%d day ago');
        if ($res[0] > '0') return $res;

        $res = $diff->format('%h hours ago');
        if ($res[0] === '1')
            $res = $diff->format('%h hour ago');
        if ($res[0] > '0') return $res;

        $res = $diff->format('%i minutes ago');
        if ($res[0] === '1')
            $res = $diff->format('%i minute ago');
        if ($res[0] > '0') return $res;

        $res = $diff->format('%s seconds ago');
        if ($res[0] === '1')
            $res = $diff->format('%s second ago');
        if ($res[0] > '0') return $res;

        return "just now";
    }
}