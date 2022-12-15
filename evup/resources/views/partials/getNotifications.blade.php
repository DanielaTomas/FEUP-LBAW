@if (!$notifications->isEmpty())
    @each('partials.notification', $notifications, 'notification')
@else
    <div class="py-2 px-4 text-center text-gray-500">You don't have any new notifications</div>
@endif
