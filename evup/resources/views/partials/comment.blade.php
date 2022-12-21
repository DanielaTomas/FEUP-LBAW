<?php if($comment->parentid == NULL) { ?>
<article id="comment{{ $comment->commentid }}" class="p-6 mb-6 text-base bg-white rounded-lg dark:bg-gray-900" data-id="{{ $comment->commentid }}">
    <footer class="flex justify-between items-center mb-2">
        <div class="flex items-center">
            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white"><img
                    class="mr-2 w-6 h-6 rounded-full" src="{{ $comment->author()->first()->userphoto }}" alt="user image">
                {{ $comment->author()->first()->username }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $comment->commentdate }}</p>
           @auth
           <?php
            if (Auth::id() == $comment->authorid || Auth::user()->usertype == "Admin") { ?>
              <div id="deleteButton-{{ $comment->commentid }}">   
                    <!-- Delete Comment Modal toggle -->
                    <button id="deleteButton-{{ $comment->commentid }}" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button" data-modal-toggle="staticModal-c{{ $comment->commentid }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>  
              </div>
              <form method="post" action="{{ route('edit_comment',[$comment->eventid,$comment->commentid]) }}">   
                        @csrf
                        <button type="submit" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                          </svg>
                        </button>
              </form>
          <?php } 
              $voted = false;
              if($comment->authorid == Auth::id()) $voted = true;
              foreach($comment->votes()->get() as $vote) { 
                    if($vote->userid == Auth::id()) $voted = true;
              } 
          ?>      
            <button onClick="like({{ $comment->eventid }},{{ $comment->commentid }},{{ $voted }})" type="submit" class="block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25l-3-3m0 0l-3 3m3-3v7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div id="likeCount-{{ $comment->commentid }}"> <?= $comment->votes()->where('type','=',true)->get()->count() ?> </div>
            </button>

            <button onClick="dislike({{ $comment->eventid }},{{ $comment->commentid }},{{ $voted }})" type="submit" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-3 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div id="dislikeCount-{{ $comment->commentid }}"> <?= $comment->votes()->where('type','=',false)->get()->count() ?> </div>
            </button>
          @endauth
        </div>
    </footer>

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
                <svg aria-hidden="true" class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
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
    <footer class="flex justify-between items-center mb-2">
        <div class="flex items-center">
            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white"><img
                    class="mr-2 w-6 h-6 rounded-full" src="{{ $reply->author()->first()->userphoto }}" alt="user image">
                {{ $reply->author()->first()->username }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $reply->commentdate }}</p>
        </div>

    </footer>
    <p class="text-gray-500 dark:text-gray-400">{{ $reply->commentcontent }}</p>
</article>
<?php } ?>
