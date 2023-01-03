<div id="eventCard{{ $event->eventid }}" class="flex flex-col w-full justify-between bg-white rounded shadow-lg sm:w-3/4 md:w-1/2 lg:w-2/5">

    <div class="w-full h-64 bg-top bg-cover rounded-t flex flex-col justify-between" style="background-image: url({{ asset('storage/images/image-'.$event->eventphoto.'.png')}})">
        @if (!$event->eventcanceled)
        @if ($event->userid !== Auth::id())
        <div class="flex justify-start gap-4 p-2 m-2 rounded-full bg-gray-900 w-40">
            <button value="Submit" data-modal-toggle="staticModal-le{{$event -> eventid}}" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="" stroke="white" class="h-8 w-8 hover:text-gray-400 ">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <p class="text-white self-center"> leave Event</p>
        </div>
        @include('partials.leave_event_modal', ['event' => $event])
        @endif
        @if (!$event->public)
        <div class="flex justify-start gap-4 p-2 m-2 rounded-full bg-gray-900 w-44">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-8 h-8">
                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
            </svg>
            <p class="text-white self-center"> private Event</p>
        </div>
        @endif
        @endif
        @if ($event->eventcanceled)
        <div class="bg-red-400 rounded-lg mx-auto text-center w-1/2 p-4 m-4">
            <h2 class="text-lg font-bold text-gray-800">This Event has been canceled.</h2>
        </div>
        @endif


    </div>
    <div class="flex flex-col w-full md:flex-row grow">
        <div class="flex flex-row justify-around p-4 font-bold leading-none text-gray-800 uppercase bg-gray-400 rounded md:flex-col md:items-center md:justify-center md:w-1/4">
            <div class="md:text-3xl">{{ $event->getDate()['startmonth'] }}</div>
            <div class="md:text-6xl">{{ $event->getDate()['startday'] }}</div>
            <div class="md:text-3xl">{{ $event->getDate()['startyear'] }}</div>
        </div>
        <div class="p-4 font-normal text-gray-800 md:w-3/4">
            <a href="{{ route('show_event', $event->eventid) }}" title="View event page">
                <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800 hover:text-indigo-600 transition ease-in-out duration-300">{{ $event->eventname }}</h1>
            </a>
            <div id="eventCardCategories{{ $event->eventid }}"> @each('partials.category', $event->eventcategories()->get(), 'category')</div>
            <p class="leading-normal">{{ $event->description }}</p>
            <div class="flex flex-column items-center mt-4 ">
                <div class="w-1/2 text-gray-700"> {{ $event->eventaddress }} </div>
                <div id="eventCardTags{{ $event->eventid }}"> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>
            </div>
        </div>
    </div>
</div>