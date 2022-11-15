<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps  = false;

    protected $table = 'Category';
    protected $primaryKey = 'categoryId';
    

    public function eventcategories()
    {
        return $this->belongsToMany(Event::class, 'CategoryEvent', 'categoryId', 'eventId');
    } 
}