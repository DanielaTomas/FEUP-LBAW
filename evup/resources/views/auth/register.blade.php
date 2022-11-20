<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    <script src="https://kit.fontawesome.com/e93bc86ff0.js" crossorigin="anonymous"></script>
</head>

<body>
    <main class="flex h-screen items-center justify-center bg-gray-900 text-white">
        <form method="POST" action="{{ route('register') }}" class="flex w-[30rem] flex-col space-y-10">
            {{ csrf_field() }}
            <div class="text-center text-4xl font-medium">Register</div>

            <p class="text-center text-lg"> Enter your information to register </p>

            <div class="w-full transform border-b-2 bg-transparent text-lg duration-300 focus-within:border-indigo-500">
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Name"
                    class="w-full border-none bg-transparent outline-none placeholder:italic focus:outline-none" />
                @if ($errors->has('name'))
                    <span class="error">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>

            <div class="w-full transform border-b-2 bg-transparent text-lg duration-300 focus-within:border-indigo-500">
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="Username"
                    class="w-full border-none bg-transparent outline-none placeholder:italic focus:outline-none" />
                @if ($errors->has('username'))
                    <span class="error">
                        {{ $errors->first('username') }}
                    </span>
                @endif
            </div>

            <div class="w-full transform border-b-2 bg-transparent text-lg duration-300 focus-within:border-indigo-500">
                <input type="email" name="email" value="{{ old('email') }}" required type="text"
                    placeholder="Email"
                    class="w-full border-none bg-transparent outline-none placeholder:italic focus:outline-none" />
                @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            <div class="w-full transform border-b-2 bg-transparent text-lg duration-300 focus-within:border-indigo-500">
                <input type="password" name="password" required placeholder="Password"
                    class="w-full border-none bg-transparent outline-none placeholder:italic focus:outline-none" />
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>

            <button class="transform rounded-sm bg-indigo-600 py-2 font-bold duration-300 hover:bg-indigo-400">
                Register Now
            </button>

            <a href="{{ route('home') }}"
                class=" text-center font-medium text-indigo-500 underline-offset-4 hover:underline">Return Home</a>
        </form>
    </main>

    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
</body>

</html>