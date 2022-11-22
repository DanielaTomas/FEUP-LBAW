@extends('layouts.app')

@section('content')

<h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">Edit Event</h1>
<form method="get" class="editevent">
  <label for="event_name"><p>Name:</p></label>
  <input id="first_last_name" type="text" name="first_last_name" value="<?=$event->eventname?>">
  <label for="address"><p>Address:</p></label>
  <input id="address" type="text" name="address" value="<?=$event->address?>">
  <label for="start"><p>Start date:</p></label>
  <input id="startdate" type="date" name="startdate" value="<?=$event->startdate?>"> 
  <label for="end"><p>End date:</p></label>
  <input id="enddate" type="date" name="enddate" value="<?=$event->enddate?>"><br>
  <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full" type="submit">Save</button>
</form>

@endsection

