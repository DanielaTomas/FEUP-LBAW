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

            <form method="POST" action="{{ route('update_password') }}" class="flex w-[30rem] flex-col space-y-10">
                @csrf

                <div class="text-center text-4xl font-medium">Reset Password</div>

                @if (Session::has('status')) 
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('status') }}
                    </div>
                @endif

                @if ($errors->has('password'))
                        <span class="error">
                            {{ $errors->first('password') }}
                        </span>
                    @endif

                <div class="w-full transform border-b-2 bg-transparent text-lg duration-300 focus-within:border-indigo-500">
                    <input id="password" type="password" name="password" required placeholder="Password"
                        class="w-full border-none bg-transparent outline-none placeholder:italic focus:outline-none" />
                </div>

                <div class="w-full transform border-b-2 bg-transparent text-lg duration-300 focus-within:border-indigo-500">
                    <input id="password-confirm" type="password" name="password_confirmation" required onkeyup="checkPass('#password')" placeholder="Confirm Password"
                        class="w-full border-none bg-transparent outline-none placeholder:italic focus:outline-none" />
                </div>

                <button class="transform rounded-sm bg-indigo-600 py-2 font-bold duration-300 hover:bg-indigo-400">
                    RESET PASSWORD
                </button>

            </form>

        </main>
    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
</body>

</html>
