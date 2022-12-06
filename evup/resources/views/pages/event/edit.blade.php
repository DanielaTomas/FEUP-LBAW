@extends('layouts.app')

@section('content')

<h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">Edit Event Details</h1>
<form method="post" class="editEvent" action="{{ route('update_event',$event->eventid) }}">
  @csrf
  <label for="eventname"><p>Name:</p></label>
  <input class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="eventname" type="text" name="eventname" value="<?=$event->eventname?>">

  <label for="description"><p>Description:</p></label>
  <input class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="description" type="text" name="description" value="<?=$event->description?>">
<!--TODO
  <label for="eventphoto"><p>Image:</p></label>
  <input id="eventphoto" type="image" name="eventphoto" value="">
-->
  <label for="eventaddress"><p>Address:</p></label>
  <input class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="eventaddress" type="text" name="eventaddress" value="<?=$event->eventaddress?>">

  <label for="start"><p>Start date:</p></label>
  <input class="rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="startdate" type="date" name="startdate" value="<?=$event->startdate?>">

  <label for="end"><p>End date:</p></label>
  <input class="rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="enddate" type="date" name="enddate" value="<?=$event->enddate?>"><br>
  
  <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full" type="submit">Save</button>
</form>

@endsection

