<article id="comment{{ $comment->parentid }}" class="p-6 mb-6 ml-6 lg:ml-12 text-base bg-white rounded-lg dark:bg-gray-900">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center">
            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white"><img
                    class="mr-2 w-6 h-6 rounded-full" src="{{ asset('storage/images/image-'.$comment->author()->first()->userphoto.'.png')}}">
                {{ $comment->author()->first()->username }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $comment->time_diff() }}</p>
        </div>

    </div>
    <p class="text-gray-500 dark:text-gray-400">{{ $comment->commentcontent }}</p>
    @if ($comment->fileid != NULL)
    <div class="w-full h-64 bg-top bg-cover rounded-t flex flex-col justify-between" style="background-image: url({{ asset('storage/images/image-'.$comment->fileid.'.png')}})"> </div>
    @endif
</article>