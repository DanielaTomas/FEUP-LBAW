@if ($users->count() != 0)
    <div class="bg-gray-200 flex flex-col gap-2">
        @each('partials.userToInvite', $users, 'user')
    </div>
@else
    <div class="flex flex-wrap m-10 text-xl">
        There aren't any users matching your search.
    </div>
@endif
