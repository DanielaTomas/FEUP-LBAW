<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the admin panel.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function show(User $user)
    {
        return $user->usertype == 'Admin';
    }

    /**
     * Determine whether the user can ban an user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function banUser(User $user)
    {
        return $user->usertype == 'Admin';
    }

    public function unbanUser(User $user)
    {
        return $user->usertype == 'Admin';
    }

    public function closeReport(User $user)
    {
        return $user->usertype == 'Admin';
    }

    public function closeRequest(User $user)
    {
        return $user->usertype == 'Admin';
    }

}
