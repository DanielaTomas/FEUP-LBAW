<tr class="border-b border-gray-200 hover:bg-gray-100">
    <td class="py-3 px-6 text-left">
        <div class="flex items-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" src="{{$user->userphoto}}"/>
            </div>
            <p>{{$user->name}}</p>
        </div>
    </td>
    <td class="py-3 px-6 text-center">
        <div class="flex item-center justify-center">
            <div class="w-6 h-2 mr-2 transform hover:text-gray-900 transition duration-300">
                <a href="{{ route('publicProfile', ['id' => $user -> userid]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
            </div>
            <div class="w-4 mr-2 transform hover:text-gray-900 transition duration-300">
                <!-- Add Modal toggle -->
                <button id="addBtn-{{$user -> userid}}" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-a{{$user -> userid}}">
                    Add
                </button>
            </div>
        </div>
    </td>
</tr>
