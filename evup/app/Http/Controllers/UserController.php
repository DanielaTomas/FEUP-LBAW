<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Card;
use App\Models\User;
use App\Models\Event;
use App\Models\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  /**
   * Creates a new item.
   *
   * @param  int  $card_id
   * @param  Request request containing the description
   * @return Response
   */
  public function create(Request $request, $card_id)
  {
    $item = new Item();
    $item->card_id = $card_id;
    $this->authorize('create', $item);
    $item->done = false;
    $item->description = $request->input('description');
    $item->save();
    return $item;
  }

    /**
     * Updates the state of an individual item.
     *
     * @param  int  $id
     * @param  Request request containing the new state
     * @return Response
     */
    public function update(Request $request, $id)
    {
      $item = Item::find($id);
      $this->authorize('update', $item);
      $item->done = $request->input('done');
      $item->save();
      return $item;
    }


    /**
     * Display the User profile.
     *
     * @param  int $id Id of the user
     * @return View
     */
    public function show(int $id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: ' . $id);

        $userInfo = [
            'id' => $id,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'userPhoto' => $user->userPhoto,
            'accountStatus' => $user->accountStatus,
            'userType' => $user->userType,
        ];

        $isOrganizer = false;
        if (Auth::check()) {
            $isOrganizer = Auth::id() == $userInfo['id'];
        }


        $ordered_events = $user->ordered_events();
        $ordered_invites = $user->ordered_invites();


        return view('pages.user.profile', [
            'user' => $userInfo,
            'events' => $ordered_events,
            'invites' => $ordered_invites,
            'isOrganizer' => $isOrganizer,
        ]);
    }

    /**
     * Deletes an individual item.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete(Request $request, $id)
    {
      $item = Item::find($id);
      $this->authorize('delete', $item);
      $item->delete();
      return $item;
    }

}
