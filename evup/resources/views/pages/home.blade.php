@extends('layouts.app')

@section('content')

@if(Session::has('success'))
    <div class="flex p-4 mb-4 bg-green-100 rounded-lg dark:bg-green-200"> <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg> <span class="sr-only">Info</span> <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800"><span class="font-medium">Success!</span>  {{ Session::get('success') }} </div> </div>
@endif

    <section id="homeTop">
        <!-- Flowbite Search component -->
        <div class="flex-auto max-w-xl mb-4">
            <form id="searchForm"  action="{{ route('search') }}">   
                <label for="event-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300">Search Events</label>
                <div class="relative">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input name="search" type="search" id="publicSearch" class="block p-4 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Events..." required>
                </div>
            </form>
        </div>

        <button id="dropdownDefault" data-dropdown-toggle="dropdown"
            class="mb-4 text-white bg-gray-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">all. Categories <svg class="ml-2 w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg></button>
        <!-- Dropdown menu -->
        <div id="dropdown" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700">
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
                @each('partials.categoryDropDown', $categories, 'category')
                
            </ul>
        </div>

    </section>
    <article class="rounded-t-3xl">
        <section id="homeTagsSection">
            <p id="homeTagHeader">Select your interests to get event suggestions based on what you love</p>
            <div id="homeTags">
                @each('partials.tag', $tags, 'tag')
            </div>
        </section>
        <section id="homeEvents">
            @include('partials.content.publicEvents', ['events' => $events])
        </section>
    </article>
  
@endsection
