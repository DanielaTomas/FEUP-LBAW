<div data-id="{{ $event->eventid }}" class="flex flex-col w-full bg-white rounded shadow-lg sm:w-3/4 md:w-1/2 lg:w-2/5">
    <div class="w-full h-64 bg-top bg-cover rounded-t" style="background-image:   url({{ asset('storage/images/image-'.$event->eventphoto.'.png')}})">
        @if (Auth::check())
            @if (Auth::user()->joinRequests()->where('eventid', $event->eventid)->get()->count() == 0)
                <button  data-modal-toggle="staticModal-jr{{$event-> eventid}}" id="requestToJoinButton{{$event->eventid }}"
                    class="m-3 inline-flex items-center font-bold leading-sm px-3 py-1 focus:bg-blue-700 focus:text-white bg-gray-900 text-white rounded-full">request
                    to Join</button>
            @endif
            @include('partials.join_request_modal',['event'=> $event])
        @endif
    </div>
    <div class="flex flex-col w-full md:flex-row">
        <div
            class="flex flex-row justify-around p-4 font-bold leading-none text-gray-800 uppercase bg-gray-400 rounded md:flex-col md:items-center md:justify-center md:w-1/4">
            <div class="md:text-xl">{{ $event->startdate }}</div>
        </div>
        <div class="p-4 font-normal text-gray-800 md:w-3/4">
            <a href="event/{{ $event->eventid }}">
                <h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}
                </h1>
            </a>
            <div id=eventCardCategories> @each('partials.category', $event->eventcategories()->get(), 'category') </div>
            <p class="leading-normal">{{ $event->description }}</p>
            <div class="flex flex-column items-center mt-4 ">
                <div class="w-1/2 text-gray-700"> {{ $event->eventaddress }} </div>
                <div id=eventCardTags> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>
            </div>
        </div>
    </div>
</div>
