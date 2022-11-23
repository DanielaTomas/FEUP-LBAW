@extends('layouts.app')

@section('content')
    <div class="flex content-center flex-col">
        <div class="inline-flex p-2 justify-center">
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                on my Agenda
            </button>
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                past Events
            </button>
            <a href="myEvents/createEvent"class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                Create an event
            </a>
        </div>
    </div>
    <div>
        <article>
            <div></div>

            <section class="flex flex-wrap justify-center gap-2">
                @each('partials.organizerEventCard', $events, 'event')
            </section>
        </article>
    @endsection
