
<div id = "comment" data-id="{{ $comment->commentid }}"> 
    <div class="rounded-lg shadow-xl border p-8 w-3xl">
     <div class="flex justify-center items-center mb-8">
       <div class="w-1/5">
         <img class="w-11 h-11 rounded-full border border-gray-100 shadow-sm" src="{{$comment->author()->first()->userphoto}}" alt="user image" />
       </div>
       <div class="w-4/5">
         <div>
           <span class="font-bold text-gray-800">{{ $comment->author()->first()->username }}</span>
           <span class="text-gray-400"> {{ $comment->commentdate }} </span>
           <button><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
        </div>
        <div class="font-semibold">
            <p>{{ $comment->commentcontent }}</p>
         </div>
       </div>
     </div>
   </div>
</div>