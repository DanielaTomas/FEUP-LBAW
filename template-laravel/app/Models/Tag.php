<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps  = false;

    protected $table = 'tag';

    public function eventTags()
    {
        return $this->belongsToMany(Event::class, 'event_tag', 'tag_id', 'event_id');
    } 
}