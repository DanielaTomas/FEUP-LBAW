<tr class="border-b border-gray-200 hover:bg-gray-100">
    <td class="py-3 px-6 text-left">
        <div class="flex items-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" 
                @if($user->accountstatus !== 'Disabled')
                    src="{{ asset('storage/images/image-'.$user->userphoto.'.png')}}"
                @else
                    src="{{ asset('storage/images/image-1.png')}}"
                @endif
                />
            </div>
            <p>{{$user->name}}</p>
        </div>
    </td>
    <td class="py-3 px-6 text-left">
        <div class="flex justify-center">
            <span
            <?php if ($user->usertype == "User") { ?>
                class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs"
            <?php  } else if ($user->usertype == "Organizer") { ?>
                class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs"
            <?php } else ?>
                class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs" 
            >{{$user->usertype}}
            </span>
        </div>
    </td>
    <td class="py-3 px-6 text-center">
        <span id="accstatus-{{$user->userid}}"
        <?php if ($user->accountstatus == "Active") { ?>
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"
        <?php  } else if ($user->accountstatus == "Blocked") { ?>
            class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs"
        <?php } else ?>
            class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs" 
        >{{$user->accountstatus}}
        </span>
    </td>
    <td class="py-3 px-6 text-center">
        <div class="flex item-center justify-center">
            @if ($user->accountstatus != "Disabled")
                <div class="w-6 h-2 mr-2 transform hover:text-gray-900 transition duration-300">
                    <a href="{{ route('publicProfile', ['id' => $user -> userid]) }}" title="View user's profile">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                </div>
                <div class="w-6 mr-2 transform hover:text-gray-900 transition duration-300">
                    <a href="{{ route('edit_user', ['id' => $user -> userid]) }}" title="Edit user's details">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>
                </div>
                <div class="w-6 mr-2 transform hover:text-gray-900 transition duration-300">
                    <!-- Delete Modal toggle -->
                    <button id="delBtn-{{$user -> userid}}" title="Delete user account" type="button" data-modal-toggle="staticModal-du{{$user -> userid}}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                @if ($user->accountstatus != "Blocked")
                    <div class="w-4 mr-2 transform hover:text-gray-900 transition duration-300">
                        <!-- Ban Modal toggle -->
                        <button id="banBtn-{{$user -> userid}}" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-u{{$user -> userid}}">
                            Ban
                        </button>
                    </div>
                @else
                    <div class="w-4 mr-2 transform hover:text-gray-900 transition duration-300">
                        <!-- Unban Modal toggle -->
                        <button id="unbanBtn-{{$user -> userid}}" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button" data-modal-toggle="staticModal-u{{$user -> userid}}">
                            Unban
                        </button>           
                    </div>
                @endif

            @else
                <p>This account has been deleted.</p>
            @endif
        </div>
    </td>

</tr>
