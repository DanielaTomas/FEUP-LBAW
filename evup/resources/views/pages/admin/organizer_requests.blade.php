@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.4/dist/flowbite.min.css"/>
<script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>

<div>
    <h2 class="text-2xl font-semibold leading-tight">Organizer Requests</h2>
</div>
<div class="my-2 flex sm:flex-row flex-col justify-between">
    <div class="flex flex-row mb-1 sm:mb-0">
        <div class="relative">
            <select
                class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 px-8 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <option>5</option>
                <option>10</option>
                <option>20</option>
            </select>
        </div>
        <div class="relative">
            <select
                class="appearance-none h-full rounded-r border-t sm:rounded-r-none sm:border-r-0 border-r border-b block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none focus:border-l focus:border-r focus:bg-white focus:border-gray-500">
                <option>All</option>
                <option>Active</option>
                <option>Reviewd</option>
            </select>
        </div>
    </div>
</div>
<div class="overflow-x-auto">
    <div class="min-w-screen min-h-screen flex items-center justify-center font-sans overflow-hidden">
        <div class="w-full lg:w-5/6">
            <div class="bg-white shadow-md rounded my-6">
                <table class="min-w-max w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">User</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @each('partials.admin.organizer_request', $requests, 'request')
                    </tbody>
                </table>
                <div
                    class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between          ">
                    <span class="text-xs xs:text-sm text-gray-900">
                        Showing 1 to 4 of 50 Entries
                    </span>
                    <div class="inline-flex mt-2 xs:mt-0">
                        <button
                            class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-l">
                            Previous
                        </button>
                        <button
                            class="text-sm bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-r">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection