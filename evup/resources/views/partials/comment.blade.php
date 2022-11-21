
<div id = "comment" data-id="{{ $comment->commentid }}"> 
    <img class="w-6 h-6 rounded-full" src="https://randomuser.me/api/portraits/men/{{$comment->author()->first()->userid}}.jpg"/>

    <p class="text-2xl font-bold leading-none tracking-tight text-gray-800">{{ $comment->author()->first()->username }}</p>

    <p> {{ $comment->commentdate }} </p>
    <p>{{ $comment->commentcontent }}</p>
</div>