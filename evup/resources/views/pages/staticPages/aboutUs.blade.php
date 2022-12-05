@extends('layouts.app')

@section('title', "- About Us")

@section('content')

    <div class="container mx-auto">
        <img class="mx-auto" src="storage/logo3d.png" alt="EVUP Logo 3D">
        <div class="text-center my-5 p-5 bg-secondary">  
                <div class="flex flex-col md:gap-6 ">
                    <h2 class="text-2xl text-gray-900 font-bold md:text-4xl">About us</h2>
                    <p class="mt-6 text-gray-600"> EVUP (Events UP) is a project developed by a group of Informatics Engineering Students who consider that the management of University related events is significantly lacking in terms of usage and accessibility for students and event organizers.</p>
                    <p class="mt-4 text-gray-600"> The main goal of this platform is to allow Student Organizations and other academic entities to have a single space to advertise and manage upcoming events whilst giving the opportunity to offer some interaction with the users. This tool would boost the popularity of these events among students and also facilitate their promotion for the organizers.</p>
                    <p class="mt-4 text-gray-600"> This web application was developed by the following students.</p>
                </div>
            </div>
          </div>


        <div class="flex md:gap-8 justify-center mt-8">
            <div class="card w-96 rounded-xl p-8 bg-gray-900 text-white shadow-xl hover:shadow">
                <img class="w-32 mx-auto rounded-full -mt-20 border-8 border-white" src="storage/avatar_placeholder.png" alt="Daniela Tomás's Avatar">
                <div class="text-center mt-2 text-3xl font-medium">Daniela Tomás</div>
                <div class="text-center mt-2 font-medium text-sm"><a class="text-gray-500 hover:text-indigo-900" href="">@daniela</a></div>
                <div class="text-center font-normal text-lg">up202004946@edu.fc.up.pt</div>
                <div class="px-6 text-center mt-2 font-normal text-sm mb-4">I'm currently studying Computer Science at FEUP</div>
            </div>

            <div class="card w-96 rounded-xl p-8 bg-gray-900 text-white shadow-xl hover:shadow">
                <img class="w-32 mx-auto rounded-full -mt-20 border-8 border-white" src="storage/avatar_placeholder.png" alt="Hugo Almeida's Avatar">
                <div class="text-center mt-2 text-3xl font-medium">Hugo Almeida</div>
                <div class="text-center mt-2 font-medium text-sm"><a class="text-gray-500 hover:text-indigo-900" href="">@hugo</a></div>
                <div class="text-center font-normal text-lg">up202006814@edu.fe.up.pt</div>
                <div class="px-6 text-center mt-2 font-normal text-sm mb-4"><p>Description</p></div>
            </div>

            <div class="card w-96 rounded-xl p-8 bg-gray-900 text-white shadow-xl hover:shadow">
                <img class="w-32 mx-auto rounded-full -mt-20 border-8 border-white" src="storage/up202006485.png" alt="José Miguel Isidro's Avatar">
                <div class="text-center mt-2 text-3xl font-medium">José Miguel Isidro</div>
                <div class="text-center mt-2 font-medium text-sm"><a class="text-gray-500 hover:text-indigo-900" href="https://github.com/zmiguel2011">@zmiguel2011</a></div>
                <div class="text-center font-normal text-lg">up202006485@fe.up.pt</div>
                <div class="px-6 text-center mt-2 font-normal text-sm mb-4">I'm currently studying Computer Science at FEUP</div>
            </div>

            <div class="card w-96 rounded-xl p-8 bg-gray-900 text-white shadow-xl hover:shadow">
                <img class="w-32 mx-auto rounded-full -mt-20 border-8 border-white" src="storage/avatar_placeholder.png" alt="Sara Moreira Reis's Avatar">
                <div class="text-center mt-2 text-3xl font-medium">Sara Moreira Reis</div>
                <div class="text-center mt-2 font-medium text-sm"><a class="text-gray-500 hover:text-indigo-900" href="">@sara</a></div>
                <div class="text-center font-normal text-lg">up202005388@edu.fe.up.pt</div>
                <div class="px-6 text-center mt-2 font-normal  text-sm mb-4"><p>Description</p></div>
            </div>
        </div>
    </div>

    </div>

@endsection