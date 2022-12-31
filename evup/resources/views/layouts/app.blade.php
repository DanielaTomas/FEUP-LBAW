<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}  @yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/event.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/event.dashboard.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/event.comments.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/event.organizer.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/event.joinRequest.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/organizer.users.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/user.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/admin.tabs.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/admin.users.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/admin.reports.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/admin.organizerRequest.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/filter.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/notifications.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/flowbite.modal.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/myEvents.js') }} defer></script>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
    <script src="https://kit.fontawesome.com/e93bc86ff0.js" crossorigin="anonymous"></script>
</head>

<body>
    <main>
        @include('partials.navbar')
        <div id="alertcontainer" class="w-3q p-4 mt-4 ml-auto mr-auto">
            @include('partials.alerts')
        </div>
        <div id="content" class="mx-20">
            @yield('content')
        </div>
        @include('partials.footer')
    </main>
    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
    
    @auth
        <script type="text/javascript" defer> setTimeout(() => { fetchNotifications(); }, "5000") </script>
    @endauth
</body>

</html>
