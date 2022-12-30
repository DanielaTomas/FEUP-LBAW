<article id="comment{{ $comment->parentid }}" class="p-6 mb-6 ml-6 lg:ml-12 text-base bg-white rounded-lg dark:bg-gray-900">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center">
            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white"><img
                    class="mr-2 w-6 h-6 rounded-full" src="{{ $comment->author()->first()->userphoto }}">
                {{ $comment->author()->first()->username }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $comment->time_diff() }}</p>
        </div>

    </div>
    <p class="text-gray-500 dark:text-gray-400 comment-overflow">{{ $comment->commentcontent }}</p>
</article>