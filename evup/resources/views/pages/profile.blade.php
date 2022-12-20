@extends('layouts.app')

@section('title', '- Profile')

@section('content')
    <article class="rounded-t-3xl">


        @foreach ($errors->all() as $error)
            <div class="flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200"> <svg aria-hidden="true"
                    class="flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                        clip-rule="evenodd"></path>
                </svg> <span class="sr-only">Info</span>
                <div class="ml-3 text-sm font-medium text-red-700 dark:text-red-800"> <span
                        class="font-medium">Error!</span> {{ $error }} </div>
            </div>
        @endforeach


        <div class="px-6 ">
            <div class="text-center mt-2">
                <div class="mr-2">
                    <img class="mx-auto w-12 h-12 rounded-full" src="{{ asset('storage/images/image-'.$user->userphoto.'.png')}}" />
                </div>
                <h3 class="text-2xl text-slate-700 font-bold leading-normal mb-1">
                    {{ $user->name }}
                    @if (Auth::user()->usertype == 'Admin' || Auth::id() == $user->userid)
                        <button>
                            <a aria-hidden="true" href="{{ route('edit_user', ['id' => $user->userid]) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </a>
                        </button>
                    @endif
                </h3>
                <div class="text-xs mt-0 mb-2 text-slate-400 font-bold uppercase">
                    <i class="text-slate-400 opacity-75"></i>{{ $user->username }} / {{ $user->email }}
                </div>
            </div>

            <div>

                <div class="flex justify-end">
                    @if (Auth::user()->usertype != 'Organizer' && Auth::user()->hasRequest() == false)
                        <div class="mr-6 transform hover:text-gray-900 transition duration-300">
                            <button
                                class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                                type="button">
                                Request To Be Organizer
                            </button>
                        </div>
                    @endif
                    @if (Auth::user()->usertype != 'Organizer' && Auth::user()->hasRequest() == true)
                        <button id="pending{{ $user->userid }}"
                            class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                            type="button">
                            Pending
                        </button>
                    @endif
                    <div class="mr-6 transform hover:text-gray-900 transition duration-300">
                        <!-- Delete Modal toggle -->
                        <button id="delBtn-{{ $user->userid }}"
                            class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                            type="button" data-modal-toggle="staticModal-d{{ $user->userid }}">
                            Delete Account
                        </button>
                    </div>
                </div>
                <article>
                    <section id="myEventsHeader" class="m-4 text-center">
                        <h2 class="text-2xl font-semibold leading-tight">My Events</h2>
                    </section>
                    <section id="Events" class="flex flex-wrap justify-center gap-2">
                        @each('partials.eventCard', $events, 'event')
                    </section>
                </article>
                <article>
                    <div id="myInvitationsHeader" class="m-4 text-center">
                        <h2 class="text-2xl font-semibold leading-tight">My Invitations</h2>
                    </div>
                    <section id="myInvitationsProfile" class="flex flex-col p-5 max-w-2xl mx-auto">
                        @each('partials.invitation', $invites, 'invite')
                    </section>
                </article>
            </div>
        </div>
    </article>

    @include('partials.delete_modal')

@endsection
