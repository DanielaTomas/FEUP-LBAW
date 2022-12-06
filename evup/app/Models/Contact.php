<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $timestamps  = false;

    protected $table = 'contact';
    protected $primaryKey = 'contactid';

    protected $fillable = [
        'name', 'email', 'subject', 'message'
      ];
    
}