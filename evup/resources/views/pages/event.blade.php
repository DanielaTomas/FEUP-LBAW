@extends('layouts.app')

@section('content')
<article class="eventcard" data-id="{{ $event->eventid }}">
    <div class="flex flex-row p-5 items-center justify-between">
        <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}</h1>
        <button><i class="fa-solid fa-triangle-exclamation fa-2x"></i></button>
    </div>
    <section id="">
        <section id="eventImage">
            <div>
                <button><i class="fa-solid fa-bell"></i></button>
                <button><i class="fa-solid fa-lock"></i></button>
            </div>
        </section>

        <h2 class="mb-4 text-3xl leading-none tracking-tight text-gray-800">Description</h2>
        <p id=eventCardDescription> {{ $event->description }} </p>
        <div id=eventCardTags> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>

        <h2 class="mb-4 text-3xl leading-none tracking-tight text-gray-800">Comments</h2>
            <!--TODO css and add buttons invite and request to join -->
            <div class="flex items-center">
                <div id="eventCardComments"> @each('partials.comment', $event->comments()->orderBy('commentdate')->get(), 'comment')</div>
            </div>
    </section>

    <section id=eventCardLower>
        <section id="eventCardLowerLeft">
            <p id=eventCardStartDate> Start: {{ $event->startdate }} </p>
            <p id=eventCardEndDate> End: {{ $event->enddate }} </p>
        </section>
        <section id="eventCardLowerRight">
            <div id=eventCardCategories> @each('partials.category', $event->eventcategories()->get(), 'category') </div>
            <p id=eventCardLocation> Address: {{ $event->address }} </p>
        </section>
    </section>
</article>

@endsection

