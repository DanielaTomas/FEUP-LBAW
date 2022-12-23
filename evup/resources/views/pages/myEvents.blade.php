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
    <article>
        <div></div>

        <section id="myeventsarea" class="flex flex-wrap justify-center gap-2 ">
            @each('partials.eventCard', $events, 'event')
        </section>
    </article>
    @endsection