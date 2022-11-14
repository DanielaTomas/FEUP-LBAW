<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps  = false;

    protected $table = 'category';

    public function eventcategories()
    {
        return $this->belongsToMany(Event::class, 'event_category', 'category_id', 'event_id');
    } 
}