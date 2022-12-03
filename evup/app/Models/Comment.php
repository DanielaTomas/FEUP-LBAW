<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
      return $this->belongsToMany(User::class, 'vote', 'voterid', 'commentid')->withPivot('type');
    }
}