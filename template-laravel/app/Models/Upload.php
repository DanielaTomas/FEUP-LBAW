<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'Upload';
    protected $primaryKey = 'uploadId';

    public function comment() 
    {
        return $this->belongsTo(Comment::class);
    }
}
