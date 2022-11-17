<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    public $timestamps  = false;

    protected $table = 'JoinRequest';
    protected $primaryKey = 'joinRequestId';

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'joinRequestId');
    } 
}