<article class="eventcard" data-id="{{ $event->eventid }}">
    <!-- add event id-->
    <section id="eventImage">
    </section>
    <section id=eventCardLower>
        <section id="eventCardLowerLeft">
            <p id=eventCardDate> {{ $event->stardate }} </p>
            <p id=eventCardTime> 7 pm </p>
        </section>
        <section id="eventCardLowerRight">
            <p id=eventCardName> {{ $event->eventname }} </p>
            <div id=eventCardCategories>  @each('partials.category', $event->eventcategories()->get(), 'category') </div>
            <p id=eventCardDescription> {{ $event->description }} </p>
            <p id=eventCardLocation> {{ $event->address }} </p>
            <div id=eventCardTags> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>
            <button id="eventCardJoinRequest"> request to Join </button>
        </section>
    </section>
</article>
