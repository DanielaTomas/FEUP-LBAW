@extends('layouts.app')

@section('title', "- My Events")

@section('content')
<div class="flex content-center flex-col">
    <div class="inline-flex p-2 justify-center">
        <button onclick="getMyEvents(0)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
            on my Agenda
        </button>
        <button onclick="getMyEvents(1)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
            past Events
        </button>
        @if (Auth::user()->usertype=="Organizer")
        <button onclick="getOrganizingEvents()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
            Organizing
        </button>
        <a href="{{ route('create_events') }}" class=" bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
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
        You are currently not participating in any event! Check your invitations or ask to join an event in the main page.
    </div>
    @endif
</div>
@endsection