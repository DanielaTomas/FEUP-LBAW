<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Report;
use App\Models\OrganizerRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends UserController
{

    /**
     * Display the Administration Panel
     *
     * @return View
     */
    public function show()
    {
        $admin = User::find(Auth::id());
        if (is_null($admin))
            return abort(404, 'User not found');

        $this->authorize('show', $admin);
        return view('pages.admin.panel',[
            'admin' => $admin,
        ]);
    }

    /**
   * Display the User profile.
   *
   * @return View
   */
    public function view($id)
    {
        $user = User::find($id);
        if (is_null($user))
            return abort(404, 'User not found, id: ' . Auth::id());

        $ordered_events = $user->ordered_events();
        $isOrganizer = 'Organizer' == $user->usertype;

        return view('pages.user.profile', [
            'user' => $user,
            'events' => $ordered_events,
            'isorganizer' => $isorganizer,
        ]);
    }


    /**
     * Display the list of users
     *
     * @return View
     */
    public function users()
    {
        $admin = User::find(Auth::id());
        if (is_null($admin))
            return abort(404, 'User not found');
        $this->authorize('users', $admin);
        $users = User::get();
        return view('pages.admin.users',[
            'users' => $users,
        ]);
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
    $admin = User::find(Auth::id());
    if (is_null($admin))
        return abort(404, 'User not found');
    $user = User::find($id);
    if (is_null($user))
        return response()->json([
            'status' => 'Not Found',
            'msg' => 'User not found, id: '.$id,
            'errors' => ['user' => 'User not found, id: '.$id]
        ], 404);

    $this->authorize('banUser', $admin);

    $user->accountstatus = 'Blocked';

    $user->save();

    return response()->json([
        'status' => 'OK',
        'msg' => 'Successfully banned user '.$user->name,
    ], 200);
  }

  /**
   * Unbans a user
   * 
   * @param  Illuminate\Http\Request  $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function unbanUser(Request $request, int $id)
  {  
    $admin = User::find(Auth::id());
    if (is_null($admin))
        return abort(404, 'User not found');
    $user = User::find($id);
    if (is_null($user))
        return response()->json([
            'status' => 'Not Found',
            'msg' => 'User not found, id: '.$id,
            'errors' => ['user' => 'User not found, id: '.$id]
        ], 404);

    $this->authorize('banUser', $admin);

    $user->accountstatus = 'Active';

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
    $admin = User::find(Auth::id());
    if (is_null($admin))
        return abort(404, 'User not found');
    $this->authorize('reports', $admin);

    $reportsInfo = Report::orderByDesc('reportid')->get()
        ->map(function ($report) {

            $reporter = Report::find($report->reportid)->reporter();
            $reporter = Report::find($report->reportid)->reported();
            //$reporter = User::find($report->reporterid);
            //$event = Event::find($report->eventid);

            return [
                'id' => $report->id,
                'reporter' => $reporter,
                'event' => $event,
                'message' => $report->message,
                'reportstatus' => $report->reportstatus,
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
        $admin = User::find(Auth::id());
        if (is_null($admin))
            return abort(404, 'User not found');
        $report = Report::find($id);
        if (is_null($report))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'Report not found, id: '.$id,
                'errors' => ['report' => 'Report not found, id: '.$id]
            ], 404);

        $this->authorize('closeReport', $admin);

        if ($report->reportstatus)
            return response()->json([
                'status' => 'OK',
                'msg' => 'Report was already closed',
            ], 200);

        $report->reportstatus = true;
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
    $admin = User::find(Auth::id());
    if (is_null($admin))
        return abort(404, 'User not found');
    $this->authorize('organizer_requests', $admin);

    $requestsInfo = OrganizerRequest::orderByDesc('Organizerrequestid')->get()
        ->map(function ($request) {

            $requester = User::find($request->requesterid);

            return [
                'id' => $request->id,
                'requester' => $requester,
                'requeststatus' => $request->requeststatus,
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
        $admin = User::find(Auth::id());
        if (is_null($admin))
            return abort(404, 'User not found');
        $request = OrganizerRequest::find($id);
        if (is_null($request))
            return response()->json([
                'status' => 'Not Found',
                'msg' => 'Request not found, id: '.$id,
                'errors' => ['req$request' => 'Request not found, id: '.$id]
            ], 404);

        $this->authorize('closeRequest', $admin);

        if ($request->requestStatus)
            return response()->json([
                'status' => 'OK',
                'msg' => 'Request was already closed',
            ], 200);

        $request->requeststatus = false;
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
    $admin = User::find(Auth::id());
    if (is_null($admin))
        return abort(404, 'User not found');
    $request = OrganizerRequest::find($id);
    if (is_null($request))
        return response()->json([
            'status' => 'Not Found',
            'msg' => 'Request not found, id: '.$id,
            'errors' => ['req$request' => 'Request not found, id: '.$id]
        ], 404);

    $this->authorize('acceptRequest', $admin);

    if ($request->requeststatus)
        return response()->json([
            'status' => 'OK',
            'msg' => 'Request was already accepted',
        ], 200);

    $request->requeststatus = true;
    $request->save();

    return response()->json([
        'status' => 'OK',
        'msg' => 'Request was successfully accepted',
    ], 200);
  }

}
