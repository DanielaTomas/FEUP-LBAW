<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Report;
use App\Models\OrganizerRequest;

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
   * Bans a user
   * 
   * @param  Illuminate\Http\Request  $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function banUser(Request $request, int $id)
  {  
      $user = User::find($id);
      if (is_null($user))
          return response()->json([
              'status' => 'Not Found',
              'msg' => 'User not found, id: '.$id,
              'errors' => ['user' => 'User not found, id: '.$id]
          ], 404);

      $this->authorize('banUser', $user);

      if ($validator->fails())
          return response()->json([
              'status' => 'Bad Request',
              'msg' => 'Failed to ban user. Bad request',
              'errors' => $validator->errors(),
          ], 400);

      $user->accountStatus = 'Disabled';

      $user->save();

      return response()->json([
          'status' => 'OK',
          'msg' => 'Successfully banned user '.$user->name,
      ], 200);
  }

  /**
   * Page with information about all the reports
   * 
   * @return View
   */
  public function reports()
  {
      $this->authorize('reports', User::class);

      $reportsInfo = Report::orderByDesc('reportId')->get()
          ->map(function ($report) {

                $user = User::find($report->reporterId);
                $reporterInfo = [
                    'id' => $report->eventId,
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'userPhoto' => $user->userPhoto,
                    'accountStatus' => $user->accountStatus,
                    'userType' => $user->userType,
                ];

                $event = Event::find($report->eventId);
                $eventInfo = [
                    'id' => $report->eventId,
                    'eventName' => $user->eventName,
                    'public' => $user->public,
                    'address' => $user->address,
                    'description' => $user->description,
                    'eventPhoto' => $user->eventPhoto,
                ];

                return [
                    'id' => $report->id,
                    'reporter' => $reporterInfo,
                    'event' => $eventInfo,
                    'message' => $report->message,
                    'reportStatus' => $report->reportStatus,
                ];
          });

      return view('pages.admin.reports', [
          'reports' => $reportsInfo,
      ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int $id Id of the user
   * @return \Illuminate\Http\RedirectResponse
   */
  public function closeReport(int $id)
    {
        $report = Report::find($id);
        if (is_null($report))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'Report not found, id: '.$id,
                'errors' => ['report' => 'Report not found, id: '.$id]
            ], 404);

        $this->authorize('closeReport', $report);

        if ($report->reportStatus)
            return response()->json([
                'status' => 'OK',
                'msg' => 'Report was already closed',
            ], 200);

        $report->reportStatus = true;
        $report->save();

        return response()->json([
            'status' => 'OK',
            'msg' => 'Report was successfully closed',
        ], 200);
    }


/**
   * Page with information about all the organizer requests
   * 
   * @return View
   */
  public function organizer_requests()
  {
      $this->authorize('organizer_requests', User::class);

      $requestsInfo = OrganizerRequest::orderByDesc('OrganizerRequestId')->get()
          ->map(function ($request) {

                $user = User::find($report->requesterId);
                $requesterInfo = [
                    'id' => $request->eventId,
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'userPhoto' => $user->userPhoto,
                    'accountStatus' => $user->accountStatus,
                    'userType' => $user->userType,
                ];


                return [
                    'id' => $request->id,
                    'requester' => $requesterInfo,
                    'requestStatus' => $request->requestStatus,
                ];
          });

      return view('pages.admin.organizer_requests', [
          'requests' => $requestsInfo,
      ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int $id Id of the user
   * @return \Illuminate\Http\RedirectResponse
   */
  public function closeRequest(int $id)
    {
        $request = OrganizerRequest::find($id);
        if (is_null($request))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'Request not found, id: '.$id,
                'errors' => ['req$request' => 'Request not found, id: '.$id]
            ], 404);

        $this->authorize('closereq$request', $request);

        if ($request->req$requestStatus)
            return response()->json([
                'status' => 'OK',
                'msg' => 'Request was already closed',
            ], 200);

        $request->req$requestStatus = false;
        $request->save();

        return response()->json([
            'status' => 'OK',
            'msg' => 'Request was successfully closed',
        ], 200);
    }


    /**
   * Update the specified resource in storage.
   *
   * @param  int $id Id of the user
   * @return \Illuminate\Http\RedirectResponse
   */
  public function acceptRequest(int $id)
  {
      $request = OrganizerRequest::find($id);
      if (is_null($request))
          return response()->json([
              'status' => 'Not Found',
              'msg' => 'Request not found, id: '.$id,
              'errors' => ['req$request' => 'Request not found, id: '.$id]
          ], 404);

      $this->authorize('closereq$request', $request);

      if ($request->req$requestStatus)
          return response()->json([
              'status' => 'OK',
              'msg' => 'Request was already accepted',
          ], 200);

      $request->req$requestStatus = true;
      $request->save();

      return response()->json([
          'status' => 'OK',
          'msg' => 'Request was successfully accepted',
      ], 200);
  }

}
