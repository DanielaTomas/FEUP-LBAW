<article class="eventcard" data-id="{{$event -> eventid}}"> <!-- add event id-->
    <section id="eventImage">
    </section>
    <section id=eventCardLower>
        <section id="eventCardLowerLeft">
            <p id=eventCardDate> {{$event -> stardate}} </p>
            <p id=eventCardTime> Start Time </p>
        </section>
        <section id="eventCardLowerRight">
            <p id=eventCardName> {{$event -> eventname}} </p>
            <div id=eventCardCategories> Sports </div>
            <p id=eventCardDescription> {{$event -> description}}  </p>
            <p id=eventCardLocation> {{$event -> address}} </p>
            <div id=eventCardTags> tag </div>
            <button id="eventCardJoinRequest"> request to Join </button>
        </section>
    </section>
</article>
