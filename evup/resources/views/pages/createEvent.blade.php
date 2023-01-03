@extends('layouts.app')

@section('title', "- Create Event")

@section('content')
<form method="POST" action="{{ route('createEvent') }}" enctype="multipart/form-data">
    @csrf
    <div class="text-center text-4xl font-medium">create a new Event</div>
    <div class="mb-6">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">event Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
        @if ($errors->has('name'))
        <span class="error">
            {{ $errors->first('name') }}
        </span>
        @elseif ($errors->has('event_repeated'))
        <span class="error">
            {{ $errors->first('event_repeated') }}
        </span>
        @endif
    </div>
    <div class="mb-6">
        <label for="eventaddress" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">event address</label>
        <input type="text" name="eventaddress" id="eventaddress" value="{{ old('eventaddress') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
        @if ($errors->has('eventaddress'))
        <span class="error">
            {{ $errors->first('eventaddress') }}
        </span>
        @endif
    </div>
    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">event
        Description</label>
    <textarea id="description" rows="4" name="description" value="{{ old('description') }}" required class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="brief description here..."></textarea>
    @if ($errors->has('description'))
    <span class="error">
        {{ $errors->first('description') }}
    </span>
    @endif
    <div class="flex items-start mb-6 mt-6">
        <div class="flex items-center h-5">
            <input id="private" type="checkbox" name="private" value={{ old('private') ? 'checked' : '' }} class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
        </div>
        <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"> private Event</label>
    </div>

    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-6">
            <label for="startDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> start
                Date</label>
            <input type="date" name="startDate" id="startDate" value="{{ old('startDate') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            @if ($errors->has('startDate'))
            <span class="error">
                {{ $errors->first('startDate') }}
            </span>
            @endif
        </div>
        <div class="mb-6">
            <label for="endDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> end Date</label>
            <input type="date" name="endDate" id="endDate" value="{{ old('endDate') }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            @if ($errors->has('endDate'))
            <span class="error">
                {{ $errors->first('endDate') }}
            </span>
            @endif
        </div>
    </div>
    <div class="grid md:grid-cols-2 md:gap-6">
        <div class="mb-6">

            <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">event Tags</h3>
            <ul class="w-48 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @each('partials.tagOption', $tags, 'tag')
            </ul>
        </div>
        <div class="mb-6">

            <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">event Categories</h3>
            <ul class="w-48 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @each('partials.categoryOption', $categories, 'category')
            </ul>
        </div>


    </div>
    <div class="flex mb-6 flex-col ">
        <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Background
            photo</label>

        <input class="rounded-lg" type="file" id="image" name="image" accept="image/*" value="{{ old('image') }}">

        @if ($errors->has('image'))
        <div class="alert alert-danger ms-3 w-50 text-center py-1" role="alert">
            <p class="">{{ $errors->first('image') }}</p>
        </div>
        @endif
    </div>
    <button type="submit" class="text-white bg-indigo-800 hover:bg-indigo-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
</form>
@endsection