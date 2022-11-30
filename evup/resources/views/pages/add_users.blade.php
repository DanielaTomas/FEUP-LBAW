@extends('layouts.app')

@section('title', "- Add Users")

@section('content')

<link href="{{ asset('css/admin.css') }}" rel="stylesheet">

<div>
    <h2 class="text-2xl font-semibold leading-tight">Users</h2>
</div>
<div class="my-2 flex sm:flex-row justify-between">
    <div class="self-center">
        <a id="goback" href="" class="text-white right-2.5 bottom-2.5 bg-gray-900 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-blue-800">Go Back</a>
    </div>
</div>
<div class="overflow-x-auto min-h-screen">
    <div class="min-w-screen flex items-center justify-center font-sans overflow-hidden">
        <div class="w-full lg:w-5/6">
            <div class="bg-white shadow-md rounded my-6">
                <table class="min-w-max w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">User</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @each('partials.add_user', $users, 'user')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@each('partials.add_user_modal', $users, 'user')

@endsection