@extends('layouts.app')

@section('title', '- Profile')

@section('content')
<article class="rounded-t-3xl flex flex-col grow px-6 ">

    <div class="text-center mt-2 ">
        <div class="mr-2">
            <img alt="user_photo" class="mx-auto w-12 h-12 rounded-full" src="{{ asset('storage/images/image-'.$user->userphoto.'.png')}}">
        </div>
        <h3 class="text-2xl text-slate-700 font-bold leading-normal mb-1">
            {{ $user->name }}
            @if (Auth::user()->usertype == 'Admin' || Auth::id() == $user->userid)
            <button>
                <a aria-hidden="true" href="{{ route('edit_user', ['id' => $user->userid]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>
                </a>
            </button>
            @endif
        </h3>
        <div class="text-xs mt-0 mb-2 text-slate-400 font-bold uppercase">
            <i class="text-slate-400 opacity-75"></i>{{ $user->username }} / {{ $user->email }}
        </div>
    </div>

    <div class="flex flex-col grow">

        <div class="flex justify-end gap-4">
            @if (Auth::user()->usertype != 'Organizer' && Auth::user()->usertype != 'Admin' && Auth::user()->hasRequest() == false)
            <div id="orgRequest" class="mr-6 transform hover:text-gray-900 transition duration-300">
                <button id="organizerRequestButton" onclick="askOrganizer({{Auth::id()}})" title="Request to be an Organizer" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button">
                    Request To Be Organizer
                </button>
            </div>
            @endif
            @if (Auth::user()->usertype != 'Organizer' && Auth::user()->hasRequest() == true)
            <div id="pending{{ $user->userid }}" title="You have already submitted a request be an Organizer" class="block text-white bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Organizer Request Pending
            </div>
            @endif
            <div class="mr-6 transform hover:text-gray-900 transition duration-300">
                <!-- Delete Modal toggle -->
                <button id="delBtn-{{ $user->userid }}" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-d{{ $user->userid }}">
                    Delete Account
                </button>
            </div>
        </div>
        <div class="flex flex-col justify-between gap-5">
            <div>
                <section id="myEventsHeader" class="m-4 text-center">
                    <h2 class="text-2xl font-semibold leading-tight">My Events</h2>
                </section>
                @if ($events->count() != 0)
                <div id="Events" class="flex flex-wrap justify-center gap-2">
                    @each('partials.eventCard', $events, 'event')

                    @else
                    <div class="flex flex-wrap justify-center m-10 text-xl">
                        You are currently not participating in any event! Check your invitations or ask to join an event in the main page.
                    </div>
                    @endif
                </div>
                </span class="flex m-auto">
                {{ $events->links() }}
                </span>
                <div class="mb-6">
                    <div id="myInvitationsHeader" class="m-4 text-center">
                        <h2 class="text-2xl font-semibold leading-tight">My Invitations</h2>
                    </div>
                    @if ($invites->count() != 0)
                    <div id="myInvitationsProfile" class="flex flex-col p-5 max-w-2xl mx-auto">
                        @each('partials.invitation', $invites, 'invite')
                    </div>
                    <div>
                        {{ $invites->links() }}
                    </div>
                    @else
                    <div class="flex flex-wrap justify-center m-10 text-xl">
                        You currently have no invites to events!
                    </div>
                    @endif

                </div>
            </div>
        </div>

</article>

@include('partials.delete_modal')

@endsection