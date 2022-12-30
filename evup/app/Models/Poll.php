<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public $timestamps  = false;

    protected $table = 'poll';
    protected $primaryKey = 'pollid';

    protected $fillable = [
        'pollcontent'
      ];
    public function event()
    {
        return $this->belongsTo(Event::class, 'eventid');
    }

    public function poll_options()
    {
        return $this->hasMany(PollOption::class, 'pollid');
    }

    public function npoll_options()
    {
        return $this->hasMany(PollOption::class, 'pollid')->count();
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pollid');
    }

    public function hasAnswered($id)
    {
        
        foreach ($this->poll_options()->get() as $opt)
            //if ($opt->voted($id))
                return true;

        return false;
    }

    public function nranswers(){
        
        $count = 0;
        foreach ($this->poll_options()->get() as $opt)
            $count += $opt->answers()->count(); 
        
        return $count;
    }
    
}