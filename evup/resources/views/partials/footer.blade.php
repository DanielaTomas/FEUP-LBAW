<div class=" bg-gray-900">
    <div class="max-w-2xl mx-auto text-white py-10">
        <div class="text-center">
            <h3 class="text-3xl mb-3"> Follow us on our socials </h3>
            <button><i class="fa-brands fa-facebook"></i></button>
            <button><i class="fa-brands fa-twitter"></i></button>
            <button><i class="fa-brands fa-instagram"></i></button>
        </div>
        <div class="mt-28 flex flex-col md:flex-row md:justify-between items-center text-sm text-gray-400">
            <p class="order-2 md:order-1 mt-8 md:mt-0"> &copy; EvUP, 2022. </p>
            <div class="order-1 md:order-2">
                <a id="faqs" class="px-2" href="{{ url('/faqs') }}">Faqs</a>
                <a id="contactUs" class="px-2 border-l" href="{{ url('/contactUs') }}">Contact Us</a>
                <a id="aboutUs" class="px-2 border-l" href="{{ url('/aboutUs') }}">About Us</a>
                @if (Auth::check())
                    <a id="myEvents" class="px-2 border-l" href="{{ url('/myEvents') }}">My Events</a>
                @else
                    <a id="myEvents" class="px-2 border-l" href="{{ url('/') }}">My Events</a>
                @endif
            </div>
        </div>
    </div>
</div>
