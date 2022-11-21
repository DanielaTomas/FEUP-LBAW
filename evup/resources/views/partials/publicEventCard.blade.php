<div data-id="{{ $event->eventid }}" class="flex flex-col w-full bg-white rounded shadow-lg sm:w-3/4 md:w-1/2 lg:w-2/5">
    <div class="w-full h-64 bg-top bg-cover rounded-t"
        style="background-image:  url( {{ $event->eventphoto}})">
    </div>
    <div class="flex flex-col w-full md:flex-row">
        <div
            class="flex flex-row justify-around p-4 font-bold leading-none text-gray-800 uppercase bg-gray-400 rounded md:flex-col md:items-center md:justify-center md:w-1/4">
            <div class="md:text-3xl">Jan</div>
            <div class="md:text-6xl">13</div>
            <div class="md:text-xl">7 pm</div>
        </div>
        <div class="p-4 font-normal text-gray-800 md:w-3/4">
            <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}</h1>
            <div id=eventCardCategories> @each('partials.category', $event->eventcategories()->get(), 'category') </div>
            <p class="leading-normal">{{ $event->description }}</p>
            <div class="flex flex-column items-center mt-4 ">
                <div class="w-1/2 text-gray-700"> {{ $event->address }} </div>
                <div id=eventCardTags> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>
                <button id="eventCardJoinRequest"> request to Join </button>
            </div>
        </div>
    </div>
</div>
