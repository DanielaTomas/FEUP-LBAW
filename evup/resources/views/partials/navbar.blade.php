<nav class="flex justify-between bg-gray-900 text-white w-screen">
    <div class="px-5 xl:px-12 py-6 flex w-full items-center">
        <a class="text-3xl font-bold font-heading" href="{{ url('/') }}"> EvUP </a>
        <!-- Nav Links -->
        <ul class="hidden md:flex px-4 mx-auto font-semibold font-heading space-x-12">
            <li><a class="hover:text-gray-200" href="{{ url('/faqs') }}">faqs</a></li>
            <li><a class="hover:text-gray-200" href="{{ url('/aboutUs') }}">about us</a></li>
            <li><a class="hover:text-gray-200" href="{{ url('/contactUs') }}">Contact Us</a></li>
            @if (Auth::check())
                <a class="hover:text-gray-200" href="{{ url('/myEvents') }}">my Events</a>
            @else
                <a class="hover:text-gray-200" href="{{ url('/') }}">my Events</a>
            @endif
        </ul>
        <!-- Sign In / Register      -->
        @if (Auth::check())
            <a class="flex items-center hover:text-gray-200" href="{{ url('/profile') }}">
                <i class="fa-solid fa-user h-6 w-6 hover:text-gray-200"></i>
            </a>
        @else
            <a class="hover:text-gray-200" href="{{ url('/login') }}">signIn</a>
        @endif

    </div>
    </div>
    <!-- Responsive navbar -->
    <a class="navbar-burger self-center mr-12 xl:hidden" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-gray-200" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </a>
</nav>
