@extends('layouts.app')

@section('content')
    <div id=profile> Profile(icon) </div>
    <article>
        <div id="organizerTag"></div>
        <div id=nameSection>
            <p>Name Last</p>
            <i class="fa-solid fa-pencil"></i>
        </div>
        <div id="myEventsHeader">
            <h3> my Events</h3>
            <p> view all</p>
        </div>
        <section id="myEventsProfile">
            @include('partials.eventCard')
        </section>
        <div id="myInvitationsHeader">
            <h3> my Invitations</h3>
        </div>
        <section id="myInvitationsProfile">
            @include('partials.invitation')
        </section>
    </article>
@endsection
