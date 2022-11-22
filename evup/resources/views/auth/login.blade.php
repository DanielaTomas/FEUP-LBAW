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

    <main class="mx-auto flex min-h-screen w-full items-center justify-center bg-gray-900 text-white">

        <form method="POST" action="{{ route('login') }}" class="flex w-[30rem] flex-col space-y-10">
            {{ csrf_field() }}
            <div class="text-center text-4xl font-medium">Log In</div>

            <div class="w-full transform border-b-2 bg-transparent text-lg duration-300 focus-within:border-indigo-500">
                <input type="email" name="email" value="{{ old('email') }}" required 
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

            @if ($errors->has('ban'))
                <div class="error text-danger text-center">
                    {{ $errors->first('ban') }}
                </div>
            @endif 

            <button class="transform rounded-sm bg-indigo-600 py-2 font-bold duration-300 hover:bg-indigo-400">
                LOG IN
            </button>

            <label class=" rounded transform text-center font-semibold text-gray-500 duration-300 hover:text-gray-300">
                <input  type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>

            <p class="text-center text-lg">
                No account?
                <a href="{{ route('register') }}" class="font-medium text-indigo-500 underline-offset-4 hover:underline">Create One</a>
            </p>
        </form>
    </main>

    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
</body>

</html>


