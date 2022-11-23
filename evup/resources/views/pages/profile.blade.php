@extends('layouts.app')

@section('content')
    <div class="relative max-w-md mx-auto md:max-w-2xl mt-6 min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-xl mt-16">
        <div class="px-6 ">
            <div class="text-center mt-2">
                <div class="mr-2">
                    <img class="mx-auto w-12 h-12 rounded-full" src="{{ $user->userphoto }}"/>
                </div>
                <h3 class="text-2xl text-slate-700 font-bold leading-normal mb-1">
                    {{ $user->name }}
                    <?php if($user->usertype == 'Admin' || Auth::id() == $user->userid) { ?>
                        <button>
                            <a aria-hidden="true" href="{{ route('edit_user', ['id' => $user->userid]) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </a>
                        </button>
                    <?php } ?>
                </h3>
                <div class="text-xs mt-0 mb-2 text-slate-400 font-bold uppercase">
                    <i class="text-slate-400 opacity-75"></i>{{ $user->username }} / {{ $user->email }}
                </div>
                
                
            </div>
        <div>
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
        <section id="myInvitationsProfile" class="flex flex-wrap justify-center gap-2">
            @each('partials.invitation', $invites, 'invite')
        </section>
    </article>
@endsection
