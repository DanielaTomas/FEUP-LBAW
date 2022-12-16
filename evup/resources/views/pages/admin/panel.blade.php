@extends('layouts.app')

@section('title', "- Admin Panel")

@section('content')

<link href="{{ asset('css/admin.css') }}" rel="stylesheet">

<div class="flex flex-wrap" id="tabs-id">
    <div class="w-full">
      <ul id="tabs-ul" class="flex mb-0 list-none justify-center flex-wrap pt-3 pb-4 flex-row">
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center cursor-pointer">
          <a id="a-tab-users" class="text-lg font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal transform hover:bg-gray-900 hover:text-white transition duration-300 ease-out hover:ease-in" onclick="setAtiveTab('tab-users')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
              </svg>
                Users
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center cursor-pointer">
          <a id="a-tab-reports" class="text-lg font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-gray-900 bg-white transform hover:bg-gray-900 hover:text-white transition duration-300 ease-out hover:ease-in" onclick="setAtiveTab('tab-reports')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
                Reports
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center cursor-pointer">
          <a id="a-tab-requests" class="text-lg font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-gray-900 bg-white transform hover:bg-gray-900 hover:text-white transition duration-300 ease-out hover:ease-in" onclick="setAtiveTab('tab-requests')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
              </svg>              
               Organizer Requests
          </a>
        </li>
      </ul>
      <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
        <div class="px-4 py-5 flex-auto">
          <div class="tab-content tab-space">
            <div class="hidden" id="tab-users">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight">Users</h2>
                </div>
                <div class="my-2 flex sm:flex-row flex-col justify-around">
                    <!-- Flowbite Search component -->
                    <div class="flex-auto max-w-2xl">
                        <form action="{{ route('users_search') }}">   
                            <label for="user-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300">Search</label>
                            <div class="relative">
                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input name="search" type="search" id="user-search" class="block p-4 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search User" required>
                                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-indigo-600 transition ease-in-out duration-300 dark:focus:ring-blue-800">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="self-center">
                        <a href="{{ route('add_user_account') }}" class="text-white right-2.5 bottom-2.5 bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-600 dark:hover:bg-indigo-600 transition ease-in-out duration-300 dark:focus:ring-blue-800">Add new User</a>
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
                                            <th class="py-3 px-6 text-center">Type</th>
                                            <th class="py-3 px-6 text-center">Status</th>
                                            <th class="py-3 px-6 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm font-light">
                                        @each('partials.admin.user', $users, 'user')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                @each('partials.admin.ban_modal', $users, 'user')
                @each('partials.admin.del_user', $users, 'user')
            </div>
            <div class="hidden" id="tab-reports">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight">Reports</h2>
                </div>
                <div class="overflow-x-auto min-h-screen">
                    <div class="min-w-screen flex items-center justify-center font-sans overflow-hidden">
                        <div class="w-full lg:w-5/6">
                            <div class="bg-white shadow-md rounded my-6">
                                <table class="min-w-max w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                            <th class="py-3 px-6 text-left">Event Reported</th>
                                            <th class="py-3 px-6 text-center">Event Status</th>
                                            <th class="py-3 px-6 text-center">Reason</th>
                                            <th class="py-3 px-6 text-center">Reporter</th>
                                            <th class="py-3 px-6 text-center">Report Status</th>
                                            <th class="py-3 px-6 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 text-sm font-light">
                                        @each('partials.admin.report', $reports, 'report')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                @each('partials.admin.close_report', $reports, 'report')
            </div>
            <div class="hidden" id="tab-requests">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight">Organizer Requests</h2>
                </div>
                <div class="overflow-x-auto min-h-screen">
                    <div class="min-w-screen flex items-center justify-center font-sans overflow-hidden">
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
                            </div>
                        </div>
                    </div>
                </div>
       
                @each('partials.admin.organizer_request_modal', $requests, 'request')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection