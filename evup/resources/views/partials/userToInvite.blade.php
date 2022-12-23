
<div id="usercard-{{ $user->userid }}" class="flex flex-row bg-white border-4 border-gray-200 justify-between">
    <td class="py-3 px-6 text-left">
        <div class="flex items-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" src="{{ asset('storage/images/image-'.$user->userphoto.'.png')}}"/>
            </div>
            <p>{{$user->name}}</p>
        </div>
    </td>
    <td class="px-4 py-2">
        <span id="email-{{ $user->userid }}">{{ $user->email }}</span>
    </td>
    <td class="px-4 py-2">
        <button onclick="inviteUser({{ $user->userid }})"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-gray-900 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Invite
            User</button>
    </td>
</div>
