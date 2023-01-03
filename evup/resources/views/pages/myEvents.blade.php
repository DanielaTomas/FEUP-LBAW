@extends('layouts.app')

@section('title', "- My Events")

@section('content')
<div class="flex content-center flex-col">
    <div class="inline-flex p-2 justify-center gap-2">
        <button onclick="getMyEvents(0)" title="View your future events" class="bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 text-white font-bold py-2 px-4 rounded-lg">
            On My Agenda
        </button>
        <button onclick="getMyEvents(1)" title="View your past events" class="bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 text-white font-bold py-2 px-4 rounded-lg">
            Past Events
        </button>
        @if (Auth::user()->usertype=="Organizer")
        <button onclick="getOrganizingEvents()" title="View the events you're organizing" class="bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 text-white font-bold py-2 px-4 rounded-lg">
            Organizing
        </button>
        <a href="{{ route('create_events') }}" class=" bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 text-white font-bold py-2 px-4 rounded-lg">
            Create an event
        </a>
        @endif
    </div>
</div>
<div>
    @if ($events->count() != 0)
    <div id="myeventsarea" class="flex flex-wrap justify-center gap-2 ">
        @each('partials.eventCard', $events, 'event')
    </div>
    @else
    <div class="flex flex-wrap justify-center m-10 text-xl">
        You are currently not participating in any event! Check your invitations or request to join an event in the event page.
    </div>
    @endif
</div>
@endsection