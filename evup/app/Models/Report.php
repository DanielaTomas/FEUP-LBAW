<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps  = false;

    protected $table = 'report';
    protected $primaryKey = 'reportid';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'reportstatus',
    ];

    function reported() {
        return $this->belongsTo(Event::class,'eventid');
    }

    function reporter() {
        return $this->belongsTo(User::class,'reporterid');
    }
}