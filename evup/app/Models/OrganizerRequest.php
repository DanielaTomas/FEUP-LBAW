<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerRequest extends Model
{
    public $timestamps  = false;

    protected $table = 'organizerrequest';
    protected $primaryKey = 'organizerrequestid';

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'organizerRequestid');
    } 
}