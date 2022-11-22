<?php

namespace App\Policies;

use App\Models\User;

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

    /* --------- ADMIN POLICIES --------- */
    public function show(User $admin)
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
}

