<?php if($comment->parentid == NULL) { ?>
<article id="comment{{ $comment->commentid }}" class="p-6 mb-6 text-base bg-white rounded-lg dark:bg-gray-900" data-id="{{ $comment->commentid }}">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center">
            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white"><img
                    class="mr-2 w-6 h-6 rounded-full" src="{{ $comment->author()->first()->userphoto }}">
                {{ $comment->author()->first()->username }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mr-4">{{ $comment->commentdate }}</p>
           @auth
            @if (Auth::id() == $comment->authorid || Auth::user()->usertype == "Admin")
              <div id="deleteButton-{{ $comment->commentid }}">   
                    <!-- Delete Comment Modal toggle -->
                    <button id="deleteButton-{{ $comment->commentid }}" class="block text-white bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-1.5 py-1.5 text-center m-1 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button" data-modal-toggle="staticModal-c{{ $comment->commentid }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>  
              </div>
              <form method="post" action="{{ route('edit_comment',[$comment->eventid,$comment->commentid]) }}">   
                        @csrf
                        <button type="submit" class="block text-white bg-gray-900 hover:bg-indigo-600 transition ease-in-out duration-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-1.5 py-1.5 text-center m-1 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                          </svg>
                        </button>
              </form>
              @endif
          <?php 
              $voted = false;
              foreach($comment->votes()->get() as $vote) { 
                    if($vote->userid == Auth::id()) $voted = true;
              } 
          ?> 
            @if (Auth::id() !== $comment->authorid)     
                <button onClick="like({{ $comment->eventid }},{{ $comment->commentid }},{{ $voted }})" type="submit" class="inline-flex text-gray-900 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-10 h-10 fill-gray-900 hover:fill-green transition ease-in-out duration-300">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.53 5.47a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l1.72-1.72v5.69a.75.75 0 001.5 0v-5.69l1.72 1.72a.75.75 0 101.06-1.06l-3-3z" clip-rule="evenodd" />
                    </svg>
                    <span id="likeCount-{{ $comment->commentid }}" class="text-lg"> <?= $comment->votes()->where('type','=',true)->get()->count() ?> </span>
                </button>

                <button onClick="dislike({{ $comment->eventid }},{{ $comment->commentid }},{{ $voted }})" type="submit" class="inline-flex text-gray-900 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-10 h-10 fill-gray-900 hover:fill-red transition ease-in-out duration-300">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-.53 14.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V8.25a.75.75 0 00-1.5 0v5.69l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z" clip-rule="evenodd" />
                    </svg>
                    <span id="dislikeCount-{{ $comment->commentid }}" class="text-lg"> <?= $comment->votes()->where('type','=',false)->get()->count() ?> </span>
                </button>
            @endif
          @endauth
        </div>
    </div>

    <p class="text-gray-500 dark:text-gray-400">{{ $comment->commentcontent }}</p>

    <form method="post" class="mb-6" action="{{ route('create_comment', [$comment->eventid, $comment->commentid]) }}">
        @csrf
        <div class="w-full md:w-full px-3 mb-2 mt-2">
            
            <input
                class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-500 focus:outline-none focus:bg-white"
                id="commentcontent" type="text" name="commentcontent"
                placeholder="Type Your Reply" required>
            <div class="w-full md:w-full flex items-start md:w-full px-3">

            <button type="submit" class="flex items-center text-sm text-gray-700 hover:underline dark:text-gray-400">
                <svg aria-hidden="true" class="mr-1 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
                Reply
            </button>
            </div>
        </div>
    </form>
</article>
<?php } ?>

<?php foreach($comment->child_comments()->get() as $reply) { ?>
<article id="comment{{ $reply->parentid }}" class="p-6 mb-6 ml-6 lg:ml-12 text-base bg-white rounded-lg dark:bg-gray-900">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center">
            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white"><img
                    class="mr-2 w-6 h-6 rounded-full" src="{{ $reply->author()->first()->userphoto }}">
                {{ $reply->author()->first()->username }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $reply->commentdate }}</p>
        </div>

    </div>
    <p class="text-gray-500 dark:text-gray-400">{{ $reply->commentcontent }}</p>
</article>
<?php } ?>
