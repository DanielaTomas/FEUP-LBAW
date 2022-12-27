<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DateTime;

class Comment extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'comment';
    protected $primaryKey = 'commentid';


    public function author() 
    {
        return $this->belongsTo(User::class, 'authorid');
    }

    public function event() 
    {
      return $this->belongsTo(Event::class, 'eventid');
    }

    public function parent_comment() 
    {
      return $this->belongsTo(Comment::class, 'parentid');
    } 

    public function child_comments() 
    {
      return $this->hasMany(Comment::class, 'parentid');
    }

    public function uploads() 
    {
      return $this->hasMany(Upload::class, 'commentid');
    }

    public function votes()
    {
      return $this->belongsToMany(User::class, 'vote', 'commentid', 'voterid')->withPivot('type');
    }

    public function time_diff()
    {
      $now = new DateTime('now');
      $commentDate = new DateTime($this->commentdate);
      $interval = $now->diff($commentDate);
      return $this->formatTimeDiff($interval);
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