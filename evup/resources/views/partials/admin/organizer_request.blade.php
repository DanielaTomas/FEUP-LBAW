<tr class="border-b border-gray-200 hover:bg-gray-100">
    <td class="py-3 px-6 text-left">
        <div class="flex items-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/{{$request['request']->requesterid}}.jpg"/>
            </div>
            <span>{{$request['requester']->name}}</span>
        </div>
    </td>
    <td class="py-3 px-6 text-center">
        <span
        <?php if ($request['request']->requeststatus === True) { $status = 'Accepted'; ?>
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"
        <?php  } else if ($request['request']->requeststatus === False) { $status = 'Denied'; ?>
            class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs" 
        <?php } else { $status = 'Pending Review'; ?>
            class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs"
        <?php } ?>
        >{{$status}}
        </span>
    </td>
    <td class="py-3 px-6 text-center">
        <div class="flex item-center justify-around">
            <?php if ($request['request']->requeststatus === NULL) { ?>
                <form method="post" action="{{ route('organizer_request_accept', ['id' => $request['request']->organizerrequestid]) }}" class="w-4 mr-2">
                    @csrf
                    <!-- Accept Button -->
                    <input type="submit" name="button" value="Accept" class="block cursor-pointer text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                </form>
                <form method="post" action="{{ route('organizer_request_deny', ['id' => $request['request']->organizerrequestid]) }}" class="w-4 mr-2">
                    @csrf
                    <!-- Deny Button -->
                    <input type="submit" name="button" value="Deny" class="block cursor-pointer text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">         
                </form>
            <?php } else { ?>
                <p>Request Reviewed</p>
            <?php } ?>
        </div>
    </td>
</tr>
