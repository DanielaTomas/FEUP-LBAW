@extends('layouts.app')

@section('title', '- Event')

@section('content')

@if ($event->eventcanceled)
    <div class="bg-red-300 rounded-lg text-center p-6 mb-4">
        <h2 class="text-3xl font-bold tracking-tight text-gray-800">This Event has been canceled.</h2>
    </div>
@endif

    <div class="my-2 flex sm:flex-row justify-between">
        <div class="self-center">
            <a id="goback" href="" title="Go back to previous page" class="text-white right-2.5 bottom-2.5 mb-4 bg-gray-900 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-blue-800">Go Back</a>
        </div>
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

    @include('partials.cancel_event_modal')

@endsection
