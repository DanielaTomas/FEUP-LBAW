@extends('layouts.app')

@section('title', '- Event')

@section('content')

    <article data-id="{{ $event->eventid }}" class="rounded-t-3xl">

        <div class="flex flex-row items-center p-6">
            <h1 class=" text-4xl font-bold leading-none tracking-tight text-gray-800">{{ $event->eventname }}</h1>
            @auth
                <?php if($event->organizer()->first()->usertype == 'Organizer' && $event->organizer()->first()->userid == $user->userid) { ?>
                <a href="{{ route('edit_event', ['id' => $event->eventid]) }}"
                    class="self-center text-white m-4 right-2.5 bottom-2.5 bg-gray-900 hover:bg-gray-700 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </a>
                <?php } ?>
            @endauth
            <button><i class="fa-solid fa-triangle-exclamation fa-2x"></i></button>
        </div>

        <section class=" flex flex-row flex-wrap justify-between">

            <section class="flex flex-col grow p-6 max-w-xl">
                <div class="flex flex-col gap-4  rounded">
                    <div class=" h-64 bg-top bg-cover rounded-t flex flex-col  shadow-lg"
                        style="background-image: url( {{ $event->eventphoto }})">
                        @if (!$event->public)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-8 h-8  mt-2 ml-2">
                                <path fill-rule="evenodd"
                                    d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>

                    <section class="flex flex-col p-4 font-bold  text-gray-800 items-start bg-gray-500">
                        <p> Start: {{ $event->startdate }} </p>
                        <p> End: {{ $event->enddate }} </p>
                        <p> Address: {{ $event->eventaddress }} </p>
                        <p> Organizer: {{ $event->organizer()->first()->username }} </p>
                    </section>

                    <div class="flex flex-row justify-center">
                        <section>
                            <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full">Request to
                                join</button>
                        </section>

                    </div>

                </div>
                @if (Auth::check())
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Invite user</h2>
                    </div>
                    <div class="flex justify-center flex-col">
                        <div class="mb-3 xl:w-96">
                            <div class="input-group relative flex  items-stretch w-full mb-4">
                                <input id="mySearch" name="search" type="search"
                                    class="form-control relative flex-auto min-w-0 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-full transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                    placeholder="Enter user's email" aria-label="Search" aria-describedby="button-addon2">
                            </div>
                        </div>
                        <div id="userResults" class="flex  flex-col gap-5 max-w-xl"> </div>
                    </div>
                @endif
            </section>

            <section class="flex flex-col  p-6 max-w-xl grow">
                <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Description</h2>
                <p class="py-4"> {{ $event->description }} </p>
                <div class="mb-4"> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>



                <section>
                    <div class=" mx-auto ">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-3xl font-bold leading-none tracking-tight text-gray-800">Comments</h2>
                        </div>
                        @if (Auth::check())
                            <form class="mb-6">
                                <div
                                    class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <label for="comment" class="sr-only">Your comment</label>
                                    <textarea id="comment" rows="6"
                                        class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                                        placeholder="Write a comment..." required></textarea>
                                </div>
                                <button type="submit"
                                    class="items-center font-bold px-3 py-1 bg-gray-800 text-white rounded-full">
                                    Post comment
                                </button>
                            </form>
                        @endif
                        @each(
                            'partials.comment',
                            $event->comments()->orderBy('commentdate')->get(),
                            'comment',
                        )
                    </div>
                </section>

            </section>
        </section>
    </article>

@endsection
