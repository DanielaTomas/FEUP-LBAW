<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps  = false;

    protected $table = 'answer';
    protected $primaryKey = ['userid','polloptionid'];
    public $incrementing = false;

}