@extends('layouts.app')

@section('content')

<div class="flex h-screen items-center justify-center bg-gray-900 text-white">
        <form method="POST" action="{{ route('create_user') }}" class="flex w-[30rem] flex-col space-y-10">
            {{ csrf_field() }}
            <div class="text-center text-4xl font-medium">Register</div>

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
            <div class="flex gap-4">
                <label class=" rounded transform text-center font-semibold text-gray-500 duration-300 hover:text-gray-300">
                    <input  type="checkbox" name="admin" {{ old('admin') ? 'checked' : '' }}> Admin
                </label>
            </div>

            <button class="transform rounded-sm bg-indigo-600 py-2 font-bold duration-300 hover:bg-indigo-400">
                add New User
            </button>
        </form>
</div>

@endsection