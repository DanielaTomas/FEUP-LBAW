<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;

class CommentController extends Controller
{

   public function create(Request $request, int $id)
   {

    $event = Event::find($id);
    if (is_null($event))
        return redirect()->back()->withErrors(['event' => 'Event not found, id: ' . $id]);

    $user = User::find(Auth::id());
    if (is_null($user))
        return abort(404, 'User not found');

     //$this->authorize('create', Comment::class);

     $validator = Validator::make(
        $request->all(),
        [
          'commentcontent' => 'required|string|min:1|max:1000',
        ]
      );
      
      if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->messages() as $key => $value) {
            $errors[$key] = is_array($value) ? implode(',', $value) : $value;
        }
        return redirect()->back()->withInput()->withErrors($errors);
     }
  
      $comment = new Comment;
      $comment->authorid = Auth::id();
      $comment->eventid = $id;
      $comment->commentcontent = $request->commentcontent;
      $comment->commentdate = date("Y-m-d");
      $comment->save();
  
      return redirect()->route('show_event',[$event->eventid]);
   }
 

  public function delete(Request $request, $id)
  {
    $comment = Comment::find($id);
    if (is_null($comment))
    return abort(404, 'Comment not found');

    $event = Event::find($comment->eventid);
    if (is_null($event))
        return abort(404, 'Event not found');

    //$this->authorize('delete', $comment);

     $comment->delete();

    return redirect()->route('show_event',[$event->eventid]);
  }

/*
  public function update(Request $request, int $id, int $eventid)
  {
      $event = Event::find($eventid);
    if (is_null($event))
        return abort(404, 'Event not found');

    $user = User::find(Auth::id());
    if (is_null($user))
        return abort(404, 'User not found');

     //$this->authorize('create', Comment::class);

     $validator = Validator::make(
        $request->all(),
        [
          'commentcontent' => 'required|string|min:1|max:1000',
        ]
      );
      
      if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors()->messages() as $key => $value) {
            $errors[$key] = is_array($value) ? implode(',', $value) : $value;
        }
        return redirect()->back()->withInput()->withErrors($errors);
     }
  
      $comment->commentcontent = $request->commentcontent;
      //$comment->commentdate = date("Y-m-d");
      $comment->save();
  
      return redirect("/event/$event->eventid");
  }
*/

}
