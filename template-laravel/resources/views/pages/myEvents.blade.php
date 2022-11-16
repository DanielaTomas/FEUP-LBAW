@extends('layouts.app')

@section('content')
    <button id=myEventsFilter> filter category </button>
    <article>
        <div id="myEventsTitle"></div>
        <section id="myEventsList">
            @include('partials.eventCard')
        </section>
    </article>
@endsection
