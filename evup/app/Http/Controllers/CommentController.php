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

  public function createComment(Request $request, int $eventid, int $parentid = null)
  {

   $event = Event::find($eventid);
   if (is_null($event))
     return abort(404, 'Event not found');

   $user = User::find(Auth::id());
   if (is_null($user))
       return abort(404, 'User not found');

   if($parentid != NULL) {
     $parent = Comment::find($parentid);
     if (is_null($parent))
       return abort(404, 'Parent Comment not found');
   }

    $this->authorize('createComment', $event);

    $validator = Validator::make(
       $request->all(),
       [
         'commentcontent' => 'required|string|min:1|max:1000',
       ]
     );
     
     if ( $validator->fails() ) {
       return response()->json([
           'status' => 'Bad Request',
           'msg' => 'Failed to create comment. Bad request',
           'errors' => $validator->errors(),
       ], 400);
   }
 
     $comment = new Comment;
     $comment->authorid = Auth::id();
     $comment->eventid = $eventid;
     if($parentid != NULL)
         $comment->parentid = $parentid;
     $comment->commentcontent = $request->commentcontent;
     $comment->save();
 
     if($parentid != NULL) {
       return response()->json([
         'status' => 'OK',
         'msg' => 'Successfully created reply',
         'html' => view('partials.reply', ['comment' => $comment,])->render(),
       ], 200);
     }
     
     return response()->json([
       'status' => 'OK',
       'msg' => 'Successfully created comment',
       'html' => view('partials.comment', ['comment' => $comment,])->render(),
     ], 200);
  
 }
 

  public function deleteComment($id, $commentid)
  {
    $comment = Comment::find($commentid);
    if (is_null($comment))
    return abort(404, 'Comment not found');

    $event = Event::find($id);
    if (is_null($event))
        return abort(404, 'Event not found');

    $this->authorize('deleteComment', $comment);

    $comment->delete();

    return response()->json([
      'status' => 'OK',
      'msg' => 'Removed comment successfully ',
      'id' => $id,
    ], 200);
    
  }

  public function updateComment(Request $request, int $id, int $commentid)
  {

    $event = Event::find($id);
    if (is_null($event))
        return redirect()->back()->withErrors(['event' => 'Event not found, id: ' . $id]);
   
    $comment = Comment::find($commentid);
    if (is_null($comment))
        return redirect()->back()->withErrors(['comment' => 'Comment not found, id: ' . $commentid]);

     $this->authorize('update', $comment);

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
  
      $comment->commentcontent = $request->commentcontent . "[edited]";
      $comment->save();
  
      /*
      return response()->json([
        'status' => 'OK',
        'msg' => 'Edit comment successfully ',
        'id' => $id,
      ], 200);
      */
      return redirect()->route('show_event',[$event->eventid]);
  }

  /*
  public function editComment(int $id, int $commentid) 
  {
    $comment = Comment::find($commentid);

    if(is_null($comment))
      return abort(404,'Comment not found');

    $this->authorize('edit',$comment);

    return view('pages.event.editComment',[
      'comment'=>$comment
    ]);
  } 
  */

  public function like(int $id, int $commentid,$voted) 
  {
    $comment = Comment::find($commentid);

    if(is_null($comment))
      return abort(404,'Comment not found');

    $this->authorize('like',$comment);
    $user = Auth::id();
    
 
    $comment->votes()->attach(Auth::id(),['commentid' => $commentid, 'voterid' => $user, 'type' => true]);

    return response()->json([
      'status' => 'OK',
      'msg' => 'Liked comment successfully ',
      'id' => $id,
    ], 200);
  }

  public function dislike(int $id, int $commentid) 
  {
    $comment = Comment::find($commentid);

    if(is_null($comment))
      return abort(404,'Comment not found');

    $this->authorize('dislike',$comment);

    $user = Auth::id();
    
    $comment->votes()->attach(Auth::id(),['commentid' => $commentid, 'voterid' => $user, 'type' => false]);

    return response()->json([
      'status' => 'OK',
      'msg' => 'Disliked comment successfully ',
      'id' => $id,
    ], 200);
  }

}
