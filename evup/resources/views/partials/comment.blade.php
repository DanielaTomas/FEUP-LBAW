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
           @auth
           <?php
            if (Auth::id() == $comment->authorid || Auth::user()->usertype == "Admin") { ?>
            <form method="post" action="{{ route('delete_comment',$comment->commentid) }}">   
                      @csrf
                      <button type="submit">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
              </form>
              <form method="post" action="{{ route('edit_comment',[$comment->eventid,$comment->commentid]) }}">   
                      @csrf
                      <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                      </button>
              </form>
          <?php } ?>
          
            <!--
            <form id="upVote" method="post" action="">
               @csrf 
                <button type="submit">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25l-3-3m0 0l-3 3m3-3v7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
            </form>

            <form id="downVote" method="post" action="">
                @csrf
                <button type="submit">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
            </form>
            -->
          @endauth
           
        </div>
        <div class="font-semibold">
            <p>{{ $comment->commentcontent }}</p>
         </div>
       </div>
     </div>
   </div>
</div>