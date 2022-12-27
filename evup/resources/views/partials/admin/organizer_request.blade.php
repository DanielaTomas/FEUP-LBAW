<tr class="border-b border-gray-200 hover:bg-gray-100">
    <td class="py-3 px-6 text-left">
        <div class="flex items-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" src="{{ asset('storage/images/image-'.$report['reporter']->userphoto.'.png')}}"/>
            </div>
            <p>{{$request['requester']->name}}</p>
        </div>
    </td>
    <td class="py-3 px-6 text-center">
        <span id="requeststatus-{{$request['request']->organizerrequestid}}"
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
                <!-- Close Report Modal toggle -->
                <button id="acceptOR-{{$request['request'] -> organizerrequestid}}" class="block cursor-pointer text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" type="button" data-modal-toggle="staticModal-org{{$request['request'] -> organizerrequestid}}">
                    Accept
                </button>
                <button id="denyOR-{{$request['request'] -> organizerrequestid}}" class="block cursor-pointer text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-denyorg{{$request['request'] -> organizerrequestid}}">
                    Deny
                </button>
               
            <?php } else { ?>
                <p>Request Reviewed</p>
            <?php } ?>
        </div>
    </td>
</tr>
