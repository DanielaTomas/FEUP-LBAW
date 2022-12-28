<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Event;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether is signed in.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function leaveEvent()
    {
        return Auth::check();
    }
    public function profile(User $user, User $userprofile)
    {
        return ($user->userid == $userprofile->userid);
    }

    public function delete(User $user, User $userprofile)
    {
        return ($user->userid == $userprofile->userid || $userprofile->usertype == 'Admin');
    }

    public function showEditForms(User $user, User $userprofile)
    {
        return ($user->userid == $userprofile->userid || $user->usertype == 'Admin');
    }
    public function update(User $user)
    {
        return (Auth::id() == $user->userid || $user->usertype == 'Admin');
    }
    /* --------- EVENT POLICIES --------- */

    public function reportEvent(User $user)
    {
        return Auth::check();
    }
    public function createEvent(User $user, User $organizer)
    {
        return $user->userid == $organizer->userid && $organizer->usertype == 'Organizer';
    }

    public function organizerEvents(User $user, User $organizer)
    {
        return $user->userid == $organizer->userid && $organizer->usertype == 'Organizer';
    }

    public function addUser(User $user, User $organizer)
    {
        return $user->userid == $organizer->userid && $organizer->usertype == 'Organizer';
    }

    public function removeUser(User $user,User $organizer)
    {
        return $user->userid == $organizer->userid && $organizer->usertype == 'Organizer';
    }

    public function invite(User $user, User $inviteddUser)
    {
        return Auth::check() && ($inviteddUser->userid != $user->userid);
    }

    public function inviteAccept(User $user, User $invitee)
    {
        return Auth::check() && $user->userid == $invitee->userid;
    }

    public function inviteDecline(User $user, User $invitee)
    {
        return Auth::check() && $user->userid == $invitee->userid;
    }

    public function organizerRequest(User $user)
    {
        return $user->usertype != 'Organizer' && $user->usertype != 'Admin';
    }

    public function requestToJoin(User $user)
    {
        return $user->usertype != 'Admin';
    }
    /* --------- ADMIN POLICIES --------- */
    public function show_panel(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function users(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function banUser(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function unbanUser(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function reports(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function closeReport(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function cancelEvent(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function organizer_requests(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function denyRequest(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    public function acceptRequest(User $admin)
    {
        return $admin->usertype == 'Admin';
    }
    public function searchUsers(User $admin)
    {
        return $admin->usertype == 'Admin';
    }

    /**
     * Determine whether the user can see and manage notifications.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function notifications(User $user)
    {
        return Auth::check();
    }
}


 


