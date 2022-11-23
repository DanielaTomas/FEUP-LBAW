@extends('layouts.app')

@section('content')
    <div class="relative max-w-md mx-auto md:max-w-2xl mt-6 min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-xl mt-16">
        <div class="px-6 ">
            <div class="text-center mt-2">
                <h3 class="text-2xl text-slate-700 font-bold leading-normal mb-1">
                    {{ $user['name'] }}
                    <button><a class="fa fa-pencil fa-fw" aria-hidden="true" href="/user/{{ $user['id'] }}/edit"></a></button>
                </h3>
                <div class="text-xs mt-0 mb-2 text-slate-400 font-bold uppercase">
                    <i class="text-slate-400 opacity-75"></i>{{ $user['username'] }} / {{ $user['email'] }}
                </div>
            </div>
        <div>
    </div>
        <div>
        <form method="post" action="{{ route('request_organizer', ['id' => $user['id']]) }}" >
            @csrf
              <button type="submit" class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-2 px-4 rounded inline-flex items-center">
            <span>Ask To Be Organizer</span>
        </button>                      
        </form>
        </div>
    <article>
        <section id="myEventsHeader">
            <p>My events</p>
            <p>Show All</p>
        </section>
        <section id="Events">
            @each('partials.eventCard', $events, 'event')
        </section>
    </article>
    <article>
        <div id="myInvitationsHeader">
                    <h3> my Invitations</h3>
        </div>
        <section id="myInvitationsProfile">
            @each('partials.invitation', $invites, 'invite')
        </section>
    </article>
@endsection
