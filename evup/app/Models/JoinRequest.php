<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    public $timestamps  = false;

    protected $table = 'joinrequest';
    protected $primaryKey = 'joinrequestid';

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'joinRequestid');
    } 
}