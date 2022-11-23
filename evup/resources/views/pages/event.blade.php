@extends('layouts.app')

@section('content')
<article class="eventcard" data-id="{{ $event->eventid }}">
    <div class="flex flex-row p-5 items-center justify-between">
        <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}</h1>
        <?php if($event->organizer()->first()->usertype == 'Organizer' && $event->organizer()->first()->userid == $user->userid) { ?>
            <a href="/event/{{$event->eventid}}/edit"><button><i class="fa-solid fa-pencil fa-2x"></i></button></a>
        <?php } ?>
        <button><i class="fa-solid fa-triangle-exclamation fa-2x"></i></button>
    </div>
    
    <section id="eventimage">
        <img src="{{ $event->eventphoto }}">
        <div>
            <button><i class="fa-solid fa-bell"></i></button>
            <?php if($event->public == false) { ?>
                <button><i class="fa-solid fa-lock"></i></button>
            <?php } ?>
        </div>
    </section>

    <section id=eventCardLower class="flex flex-row justify-around p-4 font-bold leading-none text-gray-800 bg-gray-400 md:flex-col md:items-center md:justify-center md:w-1/4">
            <p id=eventCardStartDate> Start: {{ $event->startdate }} </p>
            <p id=eventCardEndDate> End: {{ $event->enddate }} </p>
            <p id=eventCardLocation> Address: {{ $event->address }} </p>
            <p id=eventCardOrganizer> Organizer: {{ $event->organizer()->first()->username }} </p>
    </section>

    <section>
         <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full">Request to join</button>
         <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full">Invite user</button>
    </section>

    <section>
        <h2 class="mb-4 text-3xl leading-none tracking-tight text-gray-800">Description</h2>
        <p id=eventCardDescription> {{ $event->description }} </p>
         <div id=eventCardTags> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>

        <h2 class="mb-4 text-3xl leading-none tracking-tight text-gray-800">Comments</h2>
         <div class="flex items-center">
            <div id="eventCardComments"> @each('partials.comment', $event->comments()->orderBy('commentdate')->get(), 'comment')</div>
        </div>
    </section>

</article>






@endsection

