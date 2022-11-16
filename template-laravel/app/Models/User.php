<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'Users';
    protected $primaryKey = 'userId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','userPhoto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function comments() {
        return $this->hasMany(Comment::class, 'authorId');
    }

    public function votes()
    {
        return $this->belongsToMany(Comment::class, 'Vote', 'voterId', 'commentId');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporterId');
    }

}
