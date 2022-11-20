@extends('layouts.app')

@section('content')
    <div data-id="{{ $event->eventid }}" > {{ $event->eventname }} </div>
    <article>
    </article>
@endsection

