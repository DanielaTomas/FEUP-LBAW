@extends('layouts.app')

@section('content')

<h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">Edit Event Details</h1>
<form method="post" class="editEvent" action="{{ route('update_event',$event->eventid) }}">
  @csrf
  <label for="eventname"><p>Name:</p></label>
  <input id="eventname" type="text" name="eventname" value="<?=$event->eventname?>">

  <label for="description"><p>Description:</p></label>
  <input id="description" type="text" name="description" value="<?=$event->description?>">

  <label for="address"><p>Address:</p></label>
  <input id="address" type="text" name="address" value="<?=$event->address?>">

  <label for="start"><p>Start date:</p></label>
  <input id="startdate" type="date" name="startdate" value="<?=$event->startdate?>">

  <label for="end"><p>End date:</p></label>
  <input id="enddate" type="date" name="enddate" value="<?=$event->enddate?>"><br>
  
  <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full" type="submit">Save</button>
</form>

@endsection

