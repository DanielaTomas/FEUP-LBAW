<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;

class PollController extends Controller
{

    public function answerpoll(int $id){
        $user = User::find(Auth::id());
        if (is_null($user))
          return response()->json([
            'status' => 'Not Found',
            'msg' => 'User not found, id: '.Auth::id(),
            'errors' => ['user' => 'User not found, id: '.Auth::id()]
          ], 404);
    
        $polloption = PollOption::find($id);
        if (is_null($polloption))
          return response()->json([
            'status' => 'Not Found',
            'msg' => 'Polloption not found, id: '.$id,
            'errors' => ['polloption' => 'Polloption not found, id: '.$id]
          ], 404);
    
        if ($user->answers()->get()->contains($id))
          return response()->json([
            'status' => 'Not Found',
            'msg' => 'Forbidden',
            'errors' => ['forbidden' => 'Polloption alrady as a vote from this user, id: '.$id]
          ], 403);
    
        $polloption->answers()->attach(Auth::id());
        
        return response()->json([
          'status' => 'OK',
          'msg' => 'Vote was successfully accepted',
      ], 200); 
      }
    
      public function createPoll(Request $request, int $id)
      {
       $event = Event::find($id);
       if (is_null($event))
           return redirect()->back()->withErrors(['event' => 'Event not found, id: ' . $id]);
    
        $validator = Validator::make(
           $request->all(),
           [
             'question' => 'required|string|min:1|max:1000',
           ]
         );
         
         if ($validator->fails()) {
           $errors = [];
           foreach ($validator->errors()->messages() as $key => $value) {
               $errors[$key] = is_array($value) ? implode(',', $value) : $value;
           }
           return redirect()->back()->withInput()->withErrors($errors);
        }
     
         $poll = new Poll;
         $poll->eventid = $id;
         $poll->pollcontent = $request->question;
         $poll->save();
         
        foreach($request->option as $option) {
          $opt = new PollOption();
          $opt->pollid = $poll->pollid;
          $opt->optioncontent = $option;
          $opt->save();
        }
    
         return redirect()->route('show_event',[$event->eventid]);
      }
}
