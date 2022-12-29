<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    public $timestamps  = false;

    protected $table = 'appeal';
    protected $primaryKey = 'appealid';

    protected $fillable = [
        'name', 'email', 'subject', 'message'
      ];
    
}