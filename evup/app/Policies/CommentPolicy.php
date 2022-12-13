<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Comment;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    use HandlesAuthorization;

    public function deleteComment(User $user, Comment $comment)
    {
        return $user->userid == $comment->authorid;
    }

}