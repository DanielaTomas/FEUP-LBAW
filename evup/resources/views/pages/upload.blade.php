@extends('layouts.app')

@section('content')
    <h1> Upload</h1>
    <form method="POST" action="/upload" enctype="multipart/form-data"> 
        @csrf
        <input type="file" name="image">
        <input type="submit" name="upload"> 
    </form>
@endsection