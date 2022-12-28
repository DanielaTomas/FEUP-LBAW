@extends('layouts.app')

@section('title', "- Event Dashboard")

@section('content')

<link href="{{ asset('css/admin.css') }}" rel="stylesheet">

<div class="flex flex-wrap" id="tabs-id">
    <div class="w-full">
      <ul id="tabs-ul" class="flex mb-0 list-none justify-center flex-wrap pt-3 pb-4 flex-row">
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center cursor-pointer">
          <a id="o-tab-details" class="text-lg font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-gray-900 bg-white transform hover:bg-gray-900 hover:text-white transition duration-300 ease-out hover:ease-in" onclick="setEventDashboardAtiveTab('tab-details')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
                Details
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center cursor-pointer">
            <a id="o-tab-users" class="text-lg font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal transform hover:bg-gray-900 hover:text-white transition duration-300 ease-out hover:ease-in" onclick="setEventDashboardAtiveTab('tab-users')">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                Participants
            </a>
          </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center cursor-pointer">
          <a id="o-tab-requests" class="text-lg font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-gray-900 bg-white transform hover:bg-gray-900 hover:text-white transition duration-300 ease-out hover:ease-in" onclick="setEventDashboardAtiveTab('tab-requests')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
              </svg>              
               Join Requests
          </a>
        </li>
      </ul>
      <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
        <div class="px-4 py-5 flex-auto">
            <div class="tab-content tab-space">
              <div class="hidden" id="tab-details">
  
                @if ($event->eventcanceled)
                    <div class="bg-red-300 rounded-lg text-center p-6 mb-4">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-800">This Event has been canceled.</h2>
                    </div>
                @endif

                <div class="my-2 flex sm:flex-row justify-between">
                </div>
                <article data-id="{{ $event->eventid }}" class="rounded-t-3xl">
                    <div class="flex flex-row items-center p-6">
                        <h1 class=" text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}</h1>
                        @if (!$event->eventcanceled)   
                            <a href="{{ route('edit_event', ['id' => $event->eventid]) }}" title="Edit Event Details"
                                class="self-center text-white m-4 right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                        @endif
                        <button><i class="fa-solid fa-triangle-exclamation fa-2x"></i></button>
                    </div>

                    <section>
                        
                        <section class=" flex flex-row flex-wrap justify-between">

                            <section class="flex flex-col grow p-6 max-w-xl">
                                <div class="flex flex-col gap-4  rounded">
                                    <div class=" h-64 bg-top bg-cover rounded-t flex flex-col  shadow-lg"
                                        style="background-image: url( {{ $event->eventphoto }})">
                                        @if (!$event->public)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="w-8 h-8  mt-2 ml-2">
                                                <path fill-rule="evenodd"
                                                    d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>

                                    <section class="flex flex-col p-4 font-bold  text-gray-800 items-start bg-gray-500">
                                        <p> Start: {{ $event->startdate }} </p>
                                        <p> End: {{ $event->enddate }} </p>
                                        <p> Address: {{ $event->eventaddress }} </p>
                                        <p> Organizer: {{ $event->organizer()->first()->username }} </p>
                                    </section>
                                </div>
                            </section>

                            <section class="flex flex-col  p-6 max-w-xl grow">
                                <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Description</h2>
                                <p class="py-4"> {{ $event->description }} </p>
                                <div class="mb-4"> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>

                                @if (!$event->eventcanceled)   
                                    <div>
                                        <div class="items-center mb-6">
                                            <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Manage Event</h2>
                                        </div>
                                        <div class="flex flex-row justify-start items-center mb-6"> 
                                            <section>
                                                <button id="cancelEventBtn{{ $event->eventid }}" title="Cancel this event"
                                                    data-modal-toggle="staticModal-e{{ $event->eventid }}"
                                                    class="font-bold mr-4 px-4 py-2.5 bg-gray-900 hover:bg-indigo-600 mt-3 transition ease-in-out duration-300 text-white rounded-full">Cancel Event
                                                </button>
                                            </section>
                                            
                                            <button id="dropdownRadioHelperButton" data-dropdown-toggle="dropdownRadioHelper" title="Manage Event Visibility" class="ml-4 text-white rounded-full font-bold bg-gray-900 hover:bg-indigo-600 mt-3 transition ease-in-out duration-300 px-4 py-2.5 text-center inline-flex items-center" type="button">Event Visibility <svg class="ml-2 w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                
                                            <!-- Dropdown menu -->
                                            <div id="dropdownRadioHelper" class="hidden z-10 w-60 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioHelperButton">
                                                <li>
                                                    <div class="flex p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center h-5">
                                                        <input @if (!$event->public) checked @endif id="helper-radio-private" name="helper-radio" type="radio" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                    <div class="ml-2 text-sm">
                                                        <label for="helper-radio-4" class="font-medium text-gray-900 dark:text-gray-300">
                                                            <div>Private</div>
                                                            <p id="helper-radio-text-4" class="text-xs font-normal text-gray-500 dark:text-gray-300">Private events work with invite-only access.</p>
                                                        </label>
                                                    </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="flex p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="flex items-center h-5">
                                                        <input @if ($event->public) checked @endif id="helper-radio-public" name="helper-radio" type="radio" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                    <div class="ml-2 text-sm">
                                                        <label for="helper-radio-5" class="font-medium text-gray-900 dark:text-gray-300">
                                                            <div>Public</div>
                                                            <p id="helper-radio-text-5" class="text-xs font-normal text-gray-500 dark:text-gray-300">Public events can be accessed and viewed by all users.</p>
                                                        </label>
                                                    </div>
                                                    </div>
                                                </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            </section>
                        </section>
                </article>

              </div>
              <div class="hidden" id="tab-users">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight mb-4">Manage Event Participants</h2>
                </div>
                <div class="my-2 flex sm:flex-row justify-between">
                    <div class="self-center">
                                    {{-- This is 'id' is being replaced with js at runtime --> view organizer.users.js --}}
                        <a id="adduser" href="{{ route('view_add_user', ['id' => 'id']) }}" class="text-white right-2.5 bottom-2.5 bg-gray-900 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-blue-800">Add a User to the Event</a>
                    </div>
                </div>
                <div class="overflow-x-auto min-h-screen">
                    <div class="min-w-screen flex items-center justify-center font-sans overflow-hidden">
                        <div class="w-full lg:w-5/6">
                            @if (count($attendees) == 0)
                                <div class="text-center">
                                    <h2 class="text-xl font-semibold leading-tight">There are currently no participants in this event.</h2>
                                </div>
                            @else
                                <div class="bg-white shadow-md rounded my-6">
                                    <table class="min-w-max w-full table-auto">
                                        <thead>
                                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                                <th class="py-3 px-6 text-left">User</th>
                                                <th class="py-3 px-6 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 text-sm font-light">
                                            @each('partials.attendee', $attendees, 'attendee')
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @each('partials.remove_user_modal', $attendees, 'attendee')
            </div>
            <div class="hidden" id="tab-requests">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight">Join Requests</h2>
                </div>
                <div class="overflow-x-auto min-h-screen">
                    <div class="min-w-screen flex items-center justify-center font-sans overflow-hidden">
                        <div class="w-full lg:w-5/6">
                            <div class="bg-white shadow-md rounded my-6">
                                <table class="min-w-max w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                            <th class="py-3 px-6 text-left">User</th>
                                            <th class="py-3 px-6 text-center">Status</th>
                                            <th class="py-3 px-6 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm font-light">
                                        @each('partials.event.join_request', $requests, 'request')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
       
                @each('partials.event.join_request_modal', $requests, 'request')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection