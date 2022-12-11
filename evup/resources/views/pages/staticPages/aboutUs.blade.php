@extends('layouts.app')

@section('title', "- About Us")

@section('content')

    <div class="container">
        <img class="mx-auto" src="storage/logo3d.png" alt="EVUP Logo 3D">
        <div class="text-left my-5 p-5 bg-secondary">  
                <div class="flex flex-col md:gap-6 ">
                    <h2 class="text-2xl text-gray-900 font-bold md:text-4xl">About us</h2>
                    <p class="mt-6 text-gray-600"> EVUP (Events UP) is a project developed by a group of Informatics Engineering Students who consider that the management of University related events is significantly lacking in terms of usage and accessibility for students and event organizers.</p>
                    <p class="mt-4 text-gray-600"> The main goal of this platform is to allow Student Organizations and other academic entities to have a single space to advertise and manage upcoming events whilst giving the opportunity to offer some interaction with the users. This tool would boost the popularity of these events among students and also facilitate their promotion for the organizers.</p>
                    <p class="mt-4 text-gray-600"> This web application was developed by the following students.</p>
                </div>
            </div>
          </div>


        <div class="flex md:gap-8 justify-center mt-8 mb-8">
            <div class="card w-96 rounded-xl p-8 bg-gray-900 text-white shadow-xl hover:shadow">
                <img class="w-32 mx-auto rounded-full -mt-20 border-8 border-white" src="storage/avatar_placeholder.png" alt="Daniela Tomás's Avatar">
                <div class="text-center mt-2 text-3xl font-medium">Daniela Tomás</div>
                <div class="text-center mt-2 font-medium text-sm"><a class="text-gray-500 hover:text-indigo-900" href="">@daniela</a></div>
                <div class="text-center font-normal text-lg">up202004946@edu.fc.up.pt</div>
                <div class="px-6 text-center mt-2 font-normal text-sm mb-4">Description</div>
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


    <div id="accordion-flush" class="mt-16 mb-8" data-accordion="collapse" data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
        <h2 class="text-2xl text-gray-900 font-bold md:text-4xl">Main Features</h2>
        <h2 id="accordion-flush-heading-1">
            <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-1" aria-expanded="true" aria-controls="accordion-flush-body-1">
            <span>What is Flowbite?</span>
            <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </h2>
        <div id="accordion-flush-body-1" class="hidden" aria-labelledby="accordion-flush-heading-1">
            <div class="py-5 font-light border-b border-gray-200 dark:border-gray-700">
            <p class="mb-2 text-gray-500 dark:text-gray-400">Flowbite is an open-source library of interactive components built on top of Tailwind CSS including buttons, dropdowns, modals, navbars, and more.</p>
            <p class="text-gray-500 dark:text-gray-400">Check out this guide to learn how to <a href="/docs/getting-started/introduction/" class="text-blue-600 dark:text-blue-500 hover:underline">get started</a> and start developing websites even faster with components on top of Tailwind CSS.</p>
            </div>
        </div>
        <h2 id="accordion-flush-heading-2">
            <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
            <span>Is there a Figma file available?</span>
            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </h2>
        <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
            <div class="py-5 font-light border-b border-gray-200 dark:border-gray-700">
            <p class="mb-2 text-gray-500 dark:text-gray-400">Flowbite is first conceptualized and designed using the Figma software so everything you see in the library has a design equivalent in our Figma file.</p>
            <p class="text-gray-500 dark:text-gray-400">Check out the <a href="https://flowbite.com/figma/" class="text-blue-600 dark:text-blue-500 hover:underline">Figma design system</a> based on the utility classes from Tailwind CSS and components from Flowbite.</p>
            </div>
        </div>
        <h2 id="accordion-flush-heading-3">
            <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
            <span>What are the differences between Flowbite and Tailwind UI?</span>
            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </h2>
        <div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
            <div class="py-5 font-light border-b border-gray-200 dark:border-gray-700">
            <p class="mb-2 text-gray-500 dark:text-gray-400">The main difference is that the core components from Flowbite are open source under the MIT license, whereas Tailwind UI is a paid product. Another difference is that Flowbite relies on smaller and standalone components, whereas Tailwind UI offers sections of pages.</p>
            <p class="mb-2 text-gray-500 dark:text-gray-400">However, we actually recommend using both Flowbite, Flowbite Pro, and even Tailwind UI as there is no technical reason stopping you from using the best of two worlds.</p>
            <p class="mb-2 text-gray-500 dark:text-gray-400">Learn more about these technologies:</p>
            <ul class="pl-5 text-gray-500 list-disc dark:text-gray-400">
                <li><a href="https://flowbite.com/pro/" class="text-blue-600 dark:text-blue-500 hover:underline">Flowbite Pro</a></li>
                <li><a href="https://tailwindui.com/" rel="nofollow" class="text-blue-600 dark:text-blue-500 hover:underline">Tailwind UI</a></li>
            </ul>
            </div>
        </div>
    </div>

    </div>

@endsection