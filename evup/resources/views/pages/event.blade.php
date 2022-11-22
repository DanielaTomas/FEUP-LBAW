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
            <!--TODO finish comments and css -->
            <div class="flex items-center">
                <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/1.jpg"/>
                <p class="mb-4 text-2xl font-bold leading-none tracking-tight text-gray-800"> Username </p><br>
                <p class="mb-4 text-2xl leading-none tracking-tight text-gray-800"> november 12, 2022 at 7:00 pm </p>
            </div>
            <div class="flex items-center">
                <div id="eventCardComments">@each('partials.comment', $event->comments()->get(), 'comment')</div>
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
    <section> 
        <h1> Invite another user</h1>
        <div class="flex justify-center flex-col">
            <div class="mb-3 xl:w-96">
                <div class="input-group relative flex  items-stretch w-full mb-4">
                    <input id ="mySearch" name= "search" type="search"
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

    </section>
</article>

@endsection

