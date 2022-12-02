@extends('layouts.app')

@section('content')
    <section id="homeTop">
        <form id="searchForm" class="d-flex flex-row align-items-center border"
                    action="{{ route('search') }}">
            <input type="search" name="search" id="publicSearch" class="form-control rounded" placeholder="Search..">
        </form>

        <button id="dropdownDefault" data-dropdown-toggle="dropdown"
            class="text-white bg-gray-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
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
    <article>
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
