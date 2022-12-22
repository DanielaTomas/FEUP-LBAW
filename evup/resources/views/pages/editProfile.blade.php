@extends('layouts.app')

@section('title', '- Edit Profile')

@section('content')
    <!-- component -->
    <h1>Edit My Information</h1>
    <br>
    <div class="flex items-center justify-center p-12">

        <div class="mx-auto w-full max-w-[550px]">
            <form method="POST" action="{{ route('editUser', ['id' => $user->userid]) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="-mx-3 flex flex-wrap">
                    <div class="w-full px-3 sm:w-1/2">
                        <div class="mb-5">
                            <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">
                                First Name
                            </label>
                            <input required type="text" name="name" id="name" value="{{ $user->name }}"
                                class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                        </div>
                    </div>
                    <div class="w-full px-3 sm:w-1/2">
                        <div class="mb-5">
                            <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">
                                Email
                            </label>
                            <input required type="text" name="email" id="email" value="{{ $user->email }}"
                                class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                        </div>
                    </div>
                    <div class="w-full px-3 sm:w-1/2">
                        <div class="mb-5">
                            <label for="username" class="mb-3 block text-base font-medium text-[#07074D]">
                                New Username
                            </label>
                            <input required type="text" name="username" id="username" value="{{ $user->username }}"
                                class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                        </div>
                    </div>
                    <div class="w-full px-3 sm:w-1/2">
                        <div class="mb-5">
                            <label for="username" class="mb-3 block text-base font-medium text-[#07074D]">
                                New Profile Photo
                            </label>
                            <input class="rounded-lg" type="file" id="imgInput" name="image" accept="image/*">

                        </div>
                    </div>
                    <br>
                    <button type='submit'
                        class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-2 px-4 rounded inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                        </svg>
                        <span>Save</span>
                    </button>
            </form>
        </div>
    </div>
@endsection
