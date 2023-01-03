<div data-id="{{ $event->eventid }}" class="flex flex-col justify-between w-full bg-white rounded shadow-lg sm:w-3/4 md:w-1/2 lg:w-2/5">
    <div class="w-full h-64 bg-top bg-cover rounded-t" style="background-image: url({{ asset('storage/images/image-'.$event->eventphoto.'.png')}})">
        @if (!$event->eventcanceled)
            @if (Auth::check())
                @if (Auth::user()->joinRequests()->where('eventid', $event->eventid)->get()->count() == 0 && !Auth::user()->isAttending($event->eventid) && Auth::user()->usertype !== "Admin" && $event->organizer()->first()->userid !== Auth::user()->userid)
                    <button  data-modal-toggle="staticModal-jr{{$event-> eventid}}" id="requestToJoinButton{{$event->eventid }}"
                        title="Request to Join this event"
                        class="m-3 inline-flex items-center font-bold leading-sm px-4 py-2 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300  text-white rounded-lg">Request
                        to Join</button>
                @endif
                @include('partials.join_request_modal',['event'=> $event])
            @endif
        @endif
        @if ($event->eventcanceled)
            <div class="bg-red-400 rounded-lg mx-auto text-center w-1/2 p-4 m-4">
                <h2 class="text-lg font-bold text-gray-800">This Event has been canceled.</h2>
            </div>
        @endif
    </div>
    <div class="flex flex-col w-full md:flex-row grow">
        <div
            class="flex flex-row justify-around p-4 font-bold leading-none text-gray-800 uppercase bg-gray-400 rounded md:flex-col md:items-center md:justify-center md:w-1/4">
            <div class="md:text-3xl">{{ $event->getDate()['startmonth'] }}</div>
            <div class="md:text-6xl">{{ $event->getDate()['startday'] }}</div>
            <div class="md:text-3xl">{{ $event->getDate()['startyear'] }}</div>
        </div>
        <div class="p-4 font-normal text-gray-800 md:w-3/4">
            <a href="{{ route('show_event', $event->eventid) }}" title="View event page">
                <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800 hover:text-indigo-600 transition ease-in-out duration-300">{{ $event->eventname }}
                </h1>
            </a>
            <div id="eventCardCategories{{ $event->eventid }}"> @each('partials.category', $event->eventcategories()->get(), 'category') </div>
            <p class="leading-normal">{{ $event->description }}</p>
            <div class="flex flex-column items-center mt-4 ">
                <div class="w-1/2 text-gray-700"> {{ $event->eventaddress }} </div>
                <div id="eventCardTags{{ $event->eventid }}"> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>
            </div>
        </div>
    </div>
</div>
