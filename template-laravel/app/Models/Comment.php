<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'Comment';
    protected $primaryKey = 'commentId';


    public function author() 
    {
        return $this->belongsTo(User::class, 'authorId');
      }

    public function event() 
    {
      return $this->belongsTo(Event::class, 'eventId');
    }

    public function parent_comment() 
    {
      return $this->belongsTo(Comment::class, 'parentId');
    } 

    public function child_comments() 
    {
      return $this->hasMany(Comment::class, 'parentId');
    }

    public function uploads() 
    {
      return $this->hasMany(Upload::class, 'commentId');
    }

    public function votes()
    {
      return $this->belongsToMany(User::class, 'Vote', 'voterId', 'commentId');
    }
}