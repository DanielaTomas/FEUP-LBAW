<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps  = false;

    protected $table = 'Tag';
    protected $primaryKey = 'tagId';

    public function eventTags()
    {
        return $this->belongsToMany(Event::class, 'Event_Tag', 'tagId','eventId');
    } 
}