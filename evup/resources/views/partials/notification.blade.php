<button id="notification-{{ $notification['notificationid'] }}" onclick="readNotification({{ $notification['notificationid'] }})" class="flex py-3 px-4 hover:bg-gray-100 dark:hover:bg-gray-700" title="Click to mark notification as read.">
    <div class="flex-shrink-0">
    @if ($notification['notificationtype'] === "EventChange")
        <img class="w-11 h-11 rounded-full" src="{{ $notification['eventphoto'] }}" alt="Event Photo">
        <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-blue-600 rounded-full border border-white dark:border-gray-800">
            <svg class="w-3 h-3 text-white" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
        </div>
        </div>
        <div class="pl-3 w-full">
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">The event details of <a href="{{ route('show_event', $notification['eventid']) }}" title="Click to view event." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['eventname'] }}</a> have changed.</div>

    @elseif ($notification['notificationtype'] === "JoinRequestReviewed")
        <img class="w-11 h-11 rounded-full" src="{{ $notification['eventphoto'] }}" alt="Event Photo">
        <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-blue-600 rounded-full border border-white dark:border-gray-800">
            <svg class="w-3 h-3 text-white" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
        </div>
        </div>
        <div class="pl-3 w-full">
            @if ($notification['requeststatus'] === True)
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">Your request to join <a href="{{ route('show_event', $notification['eventid']) }}" title="Click to view event." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['eventname'] }}</a> has been accepted.</div>
            @elseif ($notification['requeststatus'] === False)
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">Your request to join <a href="{{ route('show_event', $notification['eventid']) }}" title="Click to view event." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['eventname'] }}</a> has been denied.</div>
            @endif

    @elseif ($notification['notificationtype'] === "OrganizerRequestReviewed")
        <img class="w-11 h-11 rounded-full" src="{{ $notification['userphoto'] }}" alt="Your Profile Photo">
        <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-blue-600 rounded-full border border-white dark:border-gray-800">
            <svg class="w-3 h-3 text-white" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
        </div>
        </div>
        <div class="pl-3 w-full">
            @if ($notification['requeststatus'] === True)
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">Your request to be organizer has been accepted.</div>
            @elseif ($notification['requeststatus'] === False)
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">Your request to be organizer has been denied.</div>
            @endif

    @elseif ($notification['notificationtype'] === "InviteReceived")
        <img class="w-11 h-11 rounded-full" src="{{ $notification['userphoto'] }}" alt="User Photo">
        <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-blue-600 rounded-full border border-white dark:border-gray-800">
            <svg class="w-3 h-3 text-white" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
        </div>
        </div>
        <div class="pl-3 w-full">
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">You have received a new invite from <a href="{{ route('publicProfile', $notification['userid']) }}" title="Click to view public profile." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['name'] }}</a> to attend <a href="{{ route('show_event', $notification['eventid']) }}" title="Click to view event." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['eventname'] }}</a>.</div>
    @elseif ($notification['notificationtype'] === "InviteAccepted")
        <img class="w-11 h-11 rounded-full" src="{{ $notification['userphoto'] }}" alt="User Photo">
        <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-blue-600 rounded-full border border-white dark:border-gray-800">
            <svg class="w-3 h-3 text-white" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
        </div>
        </div>
        <div class="pl-3 w-full">
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">Your invite to <a href="{{ route('show_event', $notification['eventid']) }}" title="Click to view event." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['eventname'] }}</a> has been accepted by <a href="{{ route('publicProfile', $notification['userid']) }}" title="Click to view public profile." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['name'] }}</a>.</div>
    @elseif ($notification['notificationtype'] === "NewPoll")
        <img class="w-11 h-11 rounded-full" src="{{ $notification['eventphoto'] }}" alt="Event Photo">
        <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-blue-600 rounded-full border border-white dark:border-gray-800">
            <svg class="w-3 h-3 text-white" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
        </div>
        </div>
        <div class="pl-3 w-full">
            <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">There is a new Poll in <a href="{{ route('show_event', $notification['eventid']) }}" title="Click to view event." class="font-semibold text-gray-900 dark:text-white hover:text-blue-700">{{ $notification['eventname'] }}</a>.</div>
   @endif
        <div class="text-xs text-blue-600 dark:text-blue-500">{{ $notification['time'] }}</div>
    </div>
</button>