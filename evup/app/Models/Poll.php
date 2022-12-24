<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public $timestamps  = false;

    protected $table = 'poll';
    protected $primaryKey = 'pollid';

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

    public function hasAnswered($id){
        
        $req = $this->poll_options()->get();
        
        for($i = 0; $i < $req->count(); ++$i) {
            $answers = $req[$i]->answers()->get();
            for ($m = 0; $m < $answers->count(); ++$m) {
            if($answers[$m]->userid==$id){
                return true;
             }
            }
        }
        return false;
    }

    public function nranswers(){
        
        $req = $this->poll_options()->get();
        $count = 0;
        for($i = 0; $i < $req->count(); ++$i) {
            $count = $count + $req[$i]->answers()->get()->count(); 
        }
        return $count;
    }
    
}