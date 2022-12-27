<tr class="border-b border-gray-200 hover:bg-gray-100">
    <td class="py-3 px-6 text-left">
        <div class="flex items-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" src="{{$report['event']->eventphoto}}"/>
            </div>
            <p>{{$report['event']->eventname}}</p>
        </div>
    </td>
    <td class="py-3 px-6 text-center">
        <span id="eventstatus-{{$report['event']->eventid}}"
        <?php if ($report['event']->eventcanceled === True) { $status = 'Canceled'; ?>
            class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs"
        <?php  } else  { $status = 'Active'; ?>
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs" 
        <?php } ?>
        >{{$status}}
        </span>
    </td>
    <td class="py-3 px-6 text-center">
        <p class="block text-white bg-gray-600 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">{{$report['report']->message}}</p>
    </td>
    <td class="py-3 px-6 text-center">
        <div class="flex justify-center">
            <div class="mr-2">
                <img class="w-6 h-6 rounded-full" 
                @if ($report['reporter']->accountstatus !== 'Disabled')
                    src="{{ asset('storage/images/image-'.$report['reporter']->userphoto.'.png')}}"
                @else
                    src="{{ asset('storage/images/image-1.png')}}"
                @endif
                />
            </div>
            <p>{{$report['reporter']->name}}</p>
        </div>
    </td>
    <td class="py-3 px-6 text-center">
        <span id="reportstatus-{{$report['report']->reportid}}"
        <?php if ($report['report']->reportstatus === True) { $status = 'Closed'; ?>
            class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs"
        <?php  } else  { $status = 'Open'; ?>
            class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs" 
        <?php } ?>
        >{{$status}}
        </span>
    </td>
    <td class="py-3 px-6 text-center">
        <div class="flex item-center justify-around">
            <?php if ($report['report']->reportstatus === True && $report['event']->eventcanceled === True)  { ?>
                <p>Report Reviewed</p>
            <?php } if ($report['report']->reportstatus === False) { ?>
                <div class="w-4 mr-2 transform hover:text-gray-900 transition duration-300">
                    <!-- Close Report Modal toggle -->
                    <button id="close-{{$report['report'] -> reportid}}" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-r{{$report['report'] -> reportid}}">
                        Close Report
                    </button>
                </div>
            <?php } if ($report['event']->eventcanceled === False) { ?>
                <div class="w-4 mr-2 transform hover:text-gray-900 transition duration-300">
                    <!-- Delete Event Modal toggle -->
                    <button id="del-{{$report['report'] -> reportid}}{{$report['event'] -> eventid}}" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button" data-modal-toggle="staticModal-r{{$report['report'] -> reportid}}{{$report['event'] -> eventid}}">
                        Delete Event
                    </button>           
                </div>
            <?php } ?>
        </div>
    </td>
</tr>
