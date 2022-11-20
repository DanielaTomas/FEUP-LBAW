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
