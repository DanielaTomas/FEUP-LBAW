@extends('layouts.app')

@section('content')

<h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">Edit Comment</h1>
<form method="post" class="editEvent" action="{{ route('update_comment',[$comment->eventid,$comment->commentid]) }}">
  @csrf
  <input class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" id="commentcontent" type="text" name="commentcontent" value="<?=rtrim($comment->commentcontent,"[edited]")?>"><br>
  <button class="items-center font-bold px-3 py-1 bg-gray-900 text-white rounded-full" type="submit">Save</button>
</form>

@endsection

