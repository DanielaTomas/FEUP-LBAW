@extends('layouts.app')

@section('content')

<h1 class="mb-4 text-4xl text-center font-bold leading-none tracking-tight text-gray-800">Edit Event Details</h1>
<form method="post" class="editEvent text-center bg-color-[#D7D3D3] rounded-t-3xl m-4 p-4" action="{{ route('update_event',$event->eventid) }}" enctype="multipart/form-data">
  @csrf
  <label for="eventname">
    <p>Name</p>
  </label>
  <input class="w-64 mb-2 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="eventname" type="text" name="eventname" value="<?= $event->eventname ?>">
  <label for="description">
    <p>Description</p>
  </label>

  <textarea class="w-64 h-32 mb-2 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="description" type="text" name="description">{{$event->description}}</textarea>
  <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Background
    photo</label>

  <input class="rounded-lg" type="file" id="image" name="image" accept="image/*" value="{{ old('image') }}">
  <label for="eventaddress">
    <p>Address</p>
  </label>

  <input class=" mb-2 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="eventaddress" type="text" name="eventaddress" value="<?= $event->eventaddress ?>">
  <label for="start">
    <p>Start date</p>
  </label>

  <input class="w-64 mb-2 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="startdate" type="date" name="startdate" value="<?= $event->startdate ?>">

  <label for="end">
    <p>End date</p>
  </label>
  <input class="w-64 mb-4 rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="enddate" type="date" name="enddate" value="<?= $event->enddate ?>"><br>
  <button class="items-center font-bold px-4 py-2 right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 text-white rounded-lg" type="submit">Save</button>
</form>

@endsection