<div id="eventCard{{ $event->eventid }}"
    class="flex flex-col w-full bg-white rounded shadow-lg sm:w-3/4 md:w-1/2 lg:w-2/5">

    <div class="w-full h-64 bg-top bg-cover rounded-t flex flex-col justify-between"
        style="background-image: url( {{ $event->eventphoto }})">
        <a href="{{ route('attendees', ['id' => $event->eventid]) }}" class="self-end text-white m-4 right-2.5 bottom-2.5 bg-gray-900 hover:bg-gray-700 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">Manage Participants</a>
            @if (!$event->public)
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8  mb-2 ml-2">
                    <path fill-rule="evenodd"
                        d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                        clip-rule="evenodd" />
                </svg>
            @endif
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
            </div>
        </div>
    </div>
</div>
