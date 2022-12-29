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

    public function update(User $user, Comment $comment)
    {
        return $user->userid == $comment->authorid;
    }

   /* 
   public function edit(User $user, Comment $comment)
    {
        return $user->userid == $comment->authorid;
    }
    */

    public function like(User $user, Comment $comment) 
    {
        if($comment->authorid != $user->userid) return false;
        foreach($comment->votes()->get() as $vote) { 
            if($vote->userid == $user) return false;
        } 
        return true;
    }

    public function dislike(User $user, Comment $comment)
    {
        if($comment->authorid != $user->userid) return false;
        foreach($comment->votes()->get() as $vote) { 
            if($vote->userid == $user) return false;
        } 
        return true;
    }
}