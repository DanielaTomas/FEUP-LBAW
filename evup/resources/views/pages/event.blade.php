@extends('layouts.app')

@section('title', "- Event")

@section('content')

<article class="eventcard" data-id="{{ $event->eventid }}">
    <div class="flex flex-row p-5 items-center justify-between">
        <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}</h1>
        @auth
        <?php if($event->organizer()->first()->usertype == 'Organizer' && $event->organizer()->first()->userid == $user->userid) { ?>
            <a href="{{route('edit_event', ['id' => $event->eventid])}}" class="self-center text-white m-4 right-2.5 bottom-2.5 bg-gray-900 hover:bg-gray-700 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
        <?php } ?>
        @endauth
        <button><i class="fa-solid fa-triangle-exclamation fa-2x"></i></button>
    </div>
    
    <section id="eventimage">
        <img src="{{ $event->eventphoto }}">
        <div>
            <button><i class="fa-solid fa-bell"></i></button>
            <?php if($event->public == false) { ?>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            <?php } ?>
        </div>
    </section>

    <section id=eventCardLower class="flex flex-row justify-around p-4 font-bold leading-none text-gray-800 bg-gray-400 md:flex-col md:items-center md:justify-center md:w-1/4">
            <p id=eventCardStartDate> Start: {{ $event->startdate }} </p>
            <p id=eventCardEndDate> End: {{ $event->enddate }} </p>
            <p id=eventCardLocation> Address: {{ $event->eventaddress }} </p>
            <p id=eventCardOrganizer> Organizer: {{ $event->organizer()->first()->username }} </p>
    </section>

    <section>
         <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full">Request to join</button>
    </section>

    <section>
        <h2 class="mb-4 text-3xl leading-none tracking-tight text-gray-800">Description</h2>
        <p id=eventCardDescription> {{ $event->description }} </p>
        <div id=eventCardTags> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>

        <h2 class="mb-4 text-3xl leading-none tracking-tight text-gray-800">Comments</h2>
        @auth
        <?php 
          if($event->organizer()->first()->userid == $user->userid || Auth::user()->isAttending($event->eventid)) { 
        ?>
            <div class="flex mx-auto items-center justify-center mt-56 mx-8 mb-4 max-w-lg">
                <form method="post" class="w-full max-w-xl" action="{{ route('create_comment',$event->eventid) }}">
                    @csrf
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <h2 class="px-4 pt-3 pb-2 text-gray-800 text-lg">Leave a comment</h2>
                        <div class="w-full md:w-full px-3 mb-2 mt-2">
                          <input class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white" id="commentcontent" type="text" name="commentcontent" placeholder="Type Your Comment" required>
                        </div>
                        <div class="w-full md:w-full flex items-start md:w-full px-3">
                            <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full" type="submit">Post Comment</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
        @endauth
        <div class="flex items-center">
         <div id="eventCardComments"> @each('partials.comment', $event->comments()->orderBy('commentdate')->get(), 'comment')</div>
        </div>
    </section>

    <h2 class="mb-4 text-3xl leading-none tracking-tight text-gray-800">Invite another user</h2>
        <div class="flex justify-center flex-col">
                <div class="mb-3 xl:w-96">
                    <div class="input-group relative flex  items-stretch w-full mb-4">
                        <input id="mySearch" name="search" type="search"
                            class="form-control relative flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                            placeholder="Enter user's email" aria-label="Search" aria-describedby="button-addon2">

                        <button
                            class="btn inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700  focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out flex items-center"
                            type="button" id="button-addon2">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" class="w-4"
                                role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            <div id="userResults" class="flex flex-wrap gap-5"> </div>
        </div>
</article>

@endsection
