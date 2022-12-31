@extends('layouts.app')

@section('title', '- Event')

@section('content')

@if ($event->eventcanceled)
<div class="bg-red-300 rounded-lg text-center p-6 mb-4">
    <h2 class="text-3xl font-bold tracking-tight text-gray-800">This Event has been canceled.</h2>
</div>
@endif

<article data-id="{{ $event->eventid }}" class="rounded-t-3xl">

    <div class="flex flex-row items-center p-6">
        <h1 class=" text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}</h1>
        @if (Auth::check())
        @if($event->userid==Auth::id())
        <a href="{{ route('attendees', ['id' => $event->eventid]) }}" class="self-end text-white m-4 right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">View Participants</a>
        @endif
        @endif
        @if (!$event->eventcanceled)
        @auth
        @if($event->organizer()->first()->usertype == 'Organizer' && $event->organizer()->first()->userid == $user->userid)
        <a href="{{ route('edit_event', ['id' => $event->eventid]) }}" class="self-center text-white m-4 right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
        </a>
        @endif
        @endauth
        @endif
        <button><i class="fa-solid fa-triangle-exclamation fa-2x"></i></button>
    </div>

    <section>

        <section class=" flex flex-row flex-wrap justify-between">

            <section class="flex flex-col grow p-6 max-w-xl">
                <div class="flex flex-col gap-4  rounded">
                    <div class=" h-64 bg-top bg-cover rounded-t flex flex-col  shadow-lg" style="background-image: url({{ asset('storage/images/image-'.$event->eventphoto.'.png')}})">
                        @if (!$event->public)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8  mt-2 ml-2">
                            <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>

                    <section class="flex flex-col p-4 font-bold  text-gray-800 items-start bg-gray-500">
                        <p> Start: {{ $event->startdate }} </p>
                        <p> End: {{ $event->enddate }} </p>
                        <p> Address: {{ $event->eventaddress }} </p>
                        <p> Organizer: {{ $event->organizer()->first()->username }} </p>
                    </section>

                    <div class="flex flex-row justify-around">
                        @if (Auth::check())
                            @if (Auth::user()->joinRequests()->where('eventid', $event->eventid)->get()->count() == 0 && !Auth::user()->isAttending($event->eventid) && Auth::user()->usertype !== "Admin")
                                <!-- Request to Join Event Modal toggle -->
                                <button id="requestToJoinButton{{ $event->eventid }}" data-modal-toggle="staticModal-jr{{ $event->eventid }}" title="Request to join this event" class="items-center text-white m-4 right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">Request to join</button>
                                @include('partials.join_request_modal', ['event' => $event])
                            @endif
                            @if (!$event->eventcanceled)
                                @if (Auth::user()->isAttending($event->eventid))
                                    @if ($event->organizer()->first()->userid !== $user->userid)
                                        <!-- Leave Event Modal toggle -->
                                        <button id="leaveEventButton{{ $event->eventid }}" data-modal-toggle="staticModal-le{{ $event->eventid }}" title="Leave this event" class="items-center text-white right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">Leave Event</button>
                                        @include('partials.leave_event_modal', ['event' => $event])
                                    @else
                                        <button id="leaveEventButton{{ $event->eventid }}" disabled title="You cannot leave your own event" class="items-center text-white right-2.5 bottom-2.5 bg-gray-600 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">Leave Event</button>
                                    @endif
                                @endif
                                @if (Auth::user()->usertype !== "Admin")
                                    <!-- Report Event Modal toggle -->
                                    <button id="reportEventButton{{ $event->eventid }}" data-modal-toggle="staticModal-re{{ $event->eventid }}" title="Report this event" class="items-center text-white right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">Report Event</button>
                                    @include('partials.report_event_modal', ['event' => $event])
                                @endif
                            @endif
                        @endif

                    </div>
                </div>

                @if (!$event->eventcanceled)
                @auth
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Invite user</h2>
                </div>
                <div class="flex justify-center flex-col">
                    <div class="mb-3 xl:w-96">
                        <div class="input-group relative flex  items-stretch w-full mb-4">
                            <input id="mySearch" name="search" type="search" class="form-control relative flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-lg transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="Enter user's email" aria-label="Search" aria-describedby="button-addon2">
                        </div>
                    </div>
                    <div id="userResults" class="flex  flex-col gap-5 max-w-xl"> </div>
                </div>
                @endauth
                @endif
            </section>

            <section class="flex flex-col  p-6 max-w-xl grow">
                <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Description</h2>
                <p class="py-4"> {{ $event->description }} </p>
                <div class="mb-4"> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>

                @auth
                @if($event->organizer()->first()->userid == $user->userid || Auth::user()->isAttending($event->eventid) || Auth::user()->usertype === "Admin")
                <section>
                    <div class=" mx-auto ">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Comments</h2>
                        </div>
                            <div class="flex mx-auto items-center justify-center max-w-lg">
                                @if (!$event->eventcanceled && Auth::user()->usertype !== "Admin")
                                    <div class="flex flex-wrap -mx-3 mb-6">
                                        <h2 class="px-4 pt-3 pb-2 text-gray-800 text-lg">Leave a comment</h2>
                                        <div class="w-full md:w-full px-3 mb-2 mt-2">
                                            <input id="commentTextArea" class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-500 focus:outline-none focus:bg-white" id="commentcontent" type="text" name="commentcontent" placeholder="Type Your Comment" required>
                                        </div>
                                        <div class="w-full md:w-full flex items-start md:w-full px-3">
                                            <button onclick="createNewComment({{ $event->eventid }})" class="items-center font-bold px-3 py-1 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 text-white rounded-lg">Post Comment</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        <section id="comments">
                            @each('partials.comment', $event->comments()->orderBy('commentdate','desc')->get(), 'comment')
                        </section>
                    </div>
                </section>
                @endif
                @endauth
            </section>
        </section>
</article>

@endsection