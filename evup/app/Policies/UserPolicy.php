<?php

namespace App\Policies;

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
    public function profile(User $user)
    {
        return Auth::id() == $user->userid;
    }
    public function showEditForms(User $user)
    {
        return (Auth::id() == $user->userid || $user->usertype == 'Admin');
    }
    public function update(User $user)
    {
        return (Auth::id() == $user->userid || $user->usertype == 'Admin');
    }
    /* --------- EVENT POLICIES --------- */

    public function organizerEvents(User $organizer)
    {
        return Auth::id() == $organizer->userid && $organizer->usertype == 'Organizer';
    }

    public function addUser(User $organizer)
    {
        return Auth::id() == $organizer->userid && $organizer->usertype == 'Organizer';
    }

    public function removeUser(User $organizer)
    {
        return Auth::id() == $organizer->userid && $organizer->usertype == 'Organizer';
    }

    public function invite( User $user, User $inviteddUser)
    {
        return Auth::check() && ($inviteddUser->userid != Auth::id());
    }

    /* --------- ADMIN POLICIES --------- */
    public function show(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function users(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function banUser(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function unbanUser(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function reports(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function closeReport(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function cancelEvent(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function organizer_requests(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function denyRequest(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }

    public function acceptRequest(User $admin)
    {
        return $admin->usertype == 'Admin';
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }
    public function searchUsers(User $admin)
    {
        return Auth::id() == $admin->userid && $admin->usertype == 'Admin';
    }
}


 


