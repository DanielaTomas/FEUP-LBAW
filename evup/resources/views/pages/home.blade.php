@extends('layouts.app')

@section('content')
    <section id="homeTop">
        <div class="searchBar">
            <input type="text" placeholder="Search..">
        </div>
        <button id="homefilter">filter category </button>
    </section>
    <article>
        <p id="homeTagHeader">Select your interests to get event suggestions based on what you love</p>
        <div id="homeTags">
            <button id="homeTag">
                <p> Tag </p>
            </button>
        </div>
        <section id="homeEvents">
            @each('partials.publicEventCard', $events, 'event')
        </section>
    </article>
@endsection
