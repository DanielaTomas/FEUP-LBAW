<tr class="border-b border-gray-200 hover:bg-gray-100">
    <td class="py-3 px-6 text-left">
        <div class="flex items-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/{{$user->userid}}.jpg"/>
            </div>
            <span>{{$user->name}}</span>
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
        <span
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
            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                <a href="/admin/users/{{$user -> userid}}/view">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
            </div>
            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                <a href="/admin/users/{{$user -> userid}}/edit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </a>
            </div>
            <?php if ($user->accountstatus != "Blocked") { ?>
                <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                    <!-- Ban Modal toggle -->
                    <button id="banBtn-{{$user -> userid}}" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-{{$user -> userid}}">
                        Ban
                    </button>
            <?php } else { ?>
                <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                    <!-- Unban Modal toggle -->
                    <button id="unbanBtn-{{$user -> userid}}" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button" data-modal-toggle="staticModal-{{$user -> userid}}">
                        Unban
                    </button>           
                </div>
            <?php } ?>
        </div>
    </td>
</tr>