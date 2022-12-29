<div class="card w-96 flex justify-center rounded-lg p-8 bg-gray-900 shadow-xl hover:shadow">

    <div class="card w-92 rounded-lg p-8 bg-gray-200 text-gray-900">
        <div class="text-center mt-2 text-3xl mb-2 font-medium">{{$contact->name}}</div>
        <div class="text-center font-normal mb-2 text-lg">{{$contact->email}}</div>
        <div class="text-center font-normal mb-2 text-lg">{{$contact->subject}}</div>
        <div class="p-8 bg-gray-300 rounded-lg text-center mt-2 font-medium text-sm mb-4">{{$contact->message}}</div>
    </div>

</div>