<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    protected $table = 'Notification';

    protected $primaryKey = 'notificationId';

    public function receiver() {
        return $this->belongsTo(User::class);
    }
}