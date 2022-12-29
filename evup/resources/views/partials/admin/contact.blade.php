<div class="card w-96 flex justify-center rounded-lg p-8 bg-gray-900 shadow-xl hover:shadow">

    <div class="card w-92 rounded-lg p-8 bg-gray-200 text-gray-900">
        <div class="text-center mt-2 text-3xl mb-2 font-medium">{{$contact->name}}</div>
        <div class="text-center font-normal mb-2 text-lg">{{$contact->email}}</div>
        <div class="flex flex-col text-center font-normal mb-2 text-lg">
            <span class="px-6 w-1/2 bg-gray-300 rounded-lg self-center mb-2 font-bold text-lg">Subject:</span>
            {{$contact->subject}}
        </div>
        <div class="flex flex-col p-8 bg-gray-300 rounded-lg text-center mt-2 font-medium text-sm mb-4">
            <span class="px-6 text-center p-1 mb-6 font-bold text-lg">Message:</span>
            {{$contact->message}}
        </div>
    </div>

</div>