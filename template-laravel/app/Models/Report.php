<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps  = false;

    protected $table = 'Report';
    protected $primaryKey = 'reportId';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'status',
    ];

    function reported() {
        return $this->belongsTo(Event::class,'eventId');
    }

    function reporter() {
        return $this->belongsTo(User::class,'reporterId');
    }
}