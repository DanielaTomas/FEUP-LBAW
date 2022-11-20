<nav class="flex justify-between bg-gray-900 text-white w-screen">
    <div class="px-5 xl:px-12 py-6 flex w-full items-center">
        <a class="text-3xl font-bold font-heading" href="{{ url('/') }}"> EvUP </a>
        <!-- Nav Links -->
        <ul class="hidden xl:flex px-4 mx-auto font-semibold font-heading space-x-12">
            @if (Auth::check())
                <a class="hover:text-gray-200" href="{{ url('/myEvents') }}">my Events</a>
            @else
                <a class="hover:text-gray-200" href="{{ url('/login') }}">my Events</a>
            @endif
            <li><a class="hover:text-gray-200" href="{{ url('/aboutUs') }}">about Us</a></li>
            <li><a class="hover:text-gray-200" href="{{ url('/contactUs') }}">contact Us</a></li>
            <li><a class="hover:text-gray-200" href="{{ url('/faqs') }}">faqs</a></li>
        </ul>
        <!-- Sign In / Register      -->
    </div>
    <!-- Responsive navbar -->
    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar2"
        class="xl:hidden p-4 self-center hover:text-gray-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-gray-200 " fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <div id="dropdownNavbar2"
        class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
        <ul class="py-1 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
            @if (Auth::check())
                <a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                    href="{{ url('/myEvents') }}">my Events</a>
            @else
                <a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                    href="{{ url('/login') }}">my Events</a>
            @endif
            <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                    href="{{ url('/aboutUs') }}">about Us</a></li>
            <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                    href="{{ url('/contactUs') }}">contact Us</a></li>
            <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                    href="{{ url('/faqs') }}">faqs</a></li>
        </ul>
    </div>

    @if (Auth::check())
        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
            class="flex mr-6 p-4 self-center hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd"
                    d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                    clip-rule="evenodd" />
            </svg>
        </button>
        <!-- Dropdown menu -->
        <div id="dropdownNavbar"
            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                <li>
                    <a href="{{ url('/profile') }}"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">profile</a>
                </li>
            </ul>
            <div class="py-1">
                <a href="{{ url('/logout') }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">sign
                    Out</a>
            </div>
        </div>
    @else
        <a class="hover:text-gray-200 font-semibold font-heading flex mr-6 p-4 self-center"
            href="{{ url('/login') }}">sign In</a>
    @endif

</nav>
