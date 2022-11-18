<footer>
    <div>
        <h2> Follow us on our socials!</h2>
    </div>
    <div>
        <button><i class="fa-brands fa-facebook"></i></button>
        <button><i class="fa-brands fa-twitter"></i></button>
        <button><i class="fa-brands fa-instagram"></i></button>
    </div>
    <div>
        <a id="faqs" href="{{ url('/faqs') }}">faqs</a>
        <a id="aboutUs" href="{{ url('/aboutUs') }}">about us</a>
        @if (Auth::check())
            <a id="myEvents" href="{{ url('/myEvents') }}">my Events</a>
        @else
            <a id="myEvents" href="{{ url('/') }}">my Events</a>
        @endif
    </div>
</footer>
