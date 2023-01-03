@if ($events->count() != 0)
    @each('partials.publicEventCard', $events, 'event')
@else
    <div class="flex flex-wrap justify-center m-10 text-xl">
        There aren't any events matching your search.
    </div>
@endif