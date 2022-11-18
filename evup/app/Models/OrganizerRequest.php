<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerRequest extends Model
{
    public $timestamps  = false;

    protected $table = 'OrganizerRequest';
    protected $primaryKey = 'organizerRequestId';

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'organizerRequestId');
    } 
}