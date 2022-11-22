
<div id = "comment" data-id="{{ $comment->commentid }}"> 
    <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/{{$comment->author()->first()->userid}}.jpg"/>

    <p class="text-2xl font-bold leading-none tracking-tight text-gray-800">{{ $comment->author()->first()->username }}</p>
    <button class="text-gray-500 text-xl"><i class="fa-solid fa-trash"></i></button>
    <p> {{ $comment->commentdate }} </p>
    <p>{{ $comment->commentcontent }}</p>
</div>

<!--
<div class="flex justify-center relative top-1/3">
     This is an example component 
    <div class="relative grid grid-cols-1 gap-4 p-4 mb-8 border rounded-lg bg-white shadow-lg">
        <div class="relative flex gap-4">
            <img src="https://randomuser.me/api/portraits/men/{{$comment->author()->first()->userid}}.jpg" class="relative rounded-lg -top-8 -mb-4 bg-white border h-5 w-5" alt="" loading="lazy">
            <div class="flex flex-col w-full">
                <div class="flex flex-row justify-between">
                    <p class="text-2xl font-bold leading-none tracking-tight text-gray-800">{{ $comment->author()->first()->username }}</p>
                        <a class="text-gray-500 text-xl" href="#"><i class="fa-solid fa-trash"></i></a>
                </div>
                <p class="text-gray-400 text-sm">{{ $comment->commentdate }}</p>
            </div>
        </div>
        <p>{{ $comment->commentcontent }}</p>
    </div>
</div>
-->