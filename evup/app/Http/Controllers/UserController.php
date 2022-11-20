<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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

      $ordered_events = $user->ordered_events();
      $ordered_invites = $user->ordered_invites();
      $isOrganizer = 'Organizer' == $userInfo['userType'];

      return view('pages.user.profile', [
          'user' => $userInfo,
          'events' => $ordered_events,
          'invites' => $ordered_invites,
          'isOrganizer' => $isOrganizer,
      ]);
  }

  /**
   * Show the form for editing the user profile.
   *
   * @param  int $id Id of the user
   * @return View
   */
  public function edit(int $id)
  {
      $user = User::find($id);
      if (is_null($user))
          return abort(404, 'User not found, id: ' . $id);

      $this->authorize('update', $user);

      $userInfo = [
        'id' => $id,
        'username' => $user->username,
        'name' => $user->name,
        'email' => $user->email,
        'userPhoto' => $user->userPhoto,
        'accountStatus' => $user->accountStatus,
        'userType' => $user->userType,
    ];

    $ordered_events = $user->ordered_events();
    $ordered_invites = $user->ordered_invites();
    $isOrganizer = 'Organizer' == $userInfo['userType'];

    return view('pages.user.profile', [
        'user' => $userInfo,
        'events' => $ordered_events,
        'invites' => $ordered_invites,
        'isOrganizer' => $isOrganizer,
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int $id Id of the user
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, int $id): RedirectResponse
  {
      $user = User::find($id);
      if (is_null($user))
          return redirect()->back()->withErrors(['user' => 'User not found, id: ' . $id]);

      $this->authorize('update', $user);

      $validator = Validator::make($request->all(), [
          'name' => 'nullable|string|max:255',
          'email' => 'nullable|string|email|max:255|unique:authenticated_user',
          'password' => 'required_with:new_password,email|string|password',
          'new_password' => 'nullable|string|min:6|confirmed',
          'userPhoto' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:4096', // max 5MB
      ]);

      if ($validator->fails()) {
          $errors = [];
          foreach ($validator->errors()->messages() as $key => $value) {
              $errors[$key] = is_array($value) ? implode(',', $value) : $value;
          }

          // Go back to form and refill it
          return redirect()->back()->withInput()->withErrors($errors);
      }

      if (isset($request->name)) $user->name = $request->name;
      if (isset($request->email)) $user->email = $request->email;
      if (isset($request->new_password)) $user->password = bcrypt($request->new_password);

      if (isset($request->userPhoto)) {
          $newuserPhoto = $request->userPhoto;
          $olduserPhoto = $user->userPhoto;

          $imgName = round(microtime(true)*1000) . '.' . $newuserPhoto->extension();
          $newuserPhoto->storeAs('public/userPhotos', $imgName);
          $user->userPhoto = $imgName;

          if (!is_null($olduserPhoto))
              Storage::delete('public/thumbnails/' . $olduserPhoto);
      }

      $user->save();

      return redirect("/user/${id}");
  }

  /**
   * Deletes a user account.
   *
   * @param  Illuminate\Http\Request  $request
   * @param int $id Id of the user
   * @return \Illuminate\Http\RedirectResponse
   */
  public function delete(Request $request, int $id): RedirectResponse
  {
      $user = User::find($id);
      if (is_null($user))
          return redirect()->back()->withErrors(['user' => 'User not found, id: ' . $id]);

      $this->authorize('delete', $user);

      $validator = Validator::make($request->all(), [
          'password' => 'required|string|password'
      ]);

      if ($validator->fails())
          return redirect()->back()->withErrors($validator->errors());

      $deleted = $user->delete();
      if ($deleted)
          return redirect('/');
      else
          return redirect()->back()->withErrors(['user' => 'Failed to delete user account. Try again later']);
  }

}
