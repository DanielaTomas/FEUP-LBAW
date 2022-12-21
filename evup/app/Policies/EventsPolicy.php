<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class EventsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether is signed in.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function list()
    {
        return Auth::check();
    }

    public function manage(User $organizer, Event $event)
    {
        return $organizer->usertype == 'Organizer' && Auth::id() == $event->userid;
    }

    public function attendees(User $organizer, Event $event)
    {
        return Auth::check();
    }

    public function edit(User $organizer, Event $event)
    {
        return $organizer->usertype == 'Organizer' && Auth::id() == $event->userid;
    }

    public function view_add_user(User $organizer, Event $event)
    {
        return $organizer->usertype == 'Organizer' && Auth::id() == $event->userid;
    }

}