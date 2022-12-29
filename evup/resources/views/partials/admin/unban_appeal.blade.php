<div class="card w-96 flex justify-center rounded-lg p-8 bg-gray-900 shadow-xl hover:shadow">

    <div class="card w-92 rounded-lg p-8 bg-gray-200 text-gray-900">
        <div class="text-center mt-2 text-3xl mb-2 font-medium">{{$appeal->name}}</div>
        <div class="text-center font-normal mb-2 text-lg">{{$appeal->email}}</div>
        <div class="flex flex-col text-center font-normal mb-2 text-lg">Unban Appeal</div>
        <div class="flex flex-col p-8 bg-gray-300 rounded-lg text-center mt-2 font-medium text-sm mb-4">
            <span class="px-6 text-center p-1 mb-6 font-bold text-lg">Message:</span>
            {{$appeal->message}}
        </div>
        @if ($appeal -> appealstatus === True)
            <div class="text-center font-bold mb-2 text-lg">Unban Appeal Accepted</div>
        @else
            <div class="text-center transform hover:text-gray-900 transition duration-300">
                <!-- Unban Modal toggle -->
                <button id="appealUnbanBtn-{{$appeal -> userid}}" class="block mx-auto text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button" data-modal-toggle="staticModal-bu{{$appeal -> userid}}">
                    Unban
                </button>           
            </div>
        @endif
    </div>

</div>

@if ($appeal -> appealstatus !== True)
    <!-- Unban main modal -->
    <div id="staticModal-bu{{$appeal -> userid}}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 p-4 w-full md:inset-0 h-modal md:h-full">
        <div class="relative w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Confirm your action
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="staticModal-bu{{$appeal -> userid}}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <h3 id="appealconfirmunbanTxt-{{$appeal -> userid}}">Would you like to unban this user?</h3>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                    <button id="appealconfirmunbanBtn-{{$appeal -> userid}}" onClick="appealUnbanUser({{$appeal -> userid}}, {{$appeal -> appealid}})" data-modal-toggle="staticModal-bu{{$appeal -> userid}}" type="button" class="unban-button text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Yes</button>
                    <button data-modal-toggle="staticModal-bu{{$appeal -> userid}}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Go Back</button>
                </div>
            </div>
        </div>
    </div>
@endif