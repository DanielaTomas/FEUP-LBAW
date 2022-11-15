<header>
    <nav id="navbarContainer">
        <a id="logo" href="{{ url('/') }}">EvUP</a>
        <div id="linksContainer">
            <a id="faqs" href="{{ url('/faqs') }}">faqs</a>
            <a id="aboutUs" href="{{ url('/aboutUs') }}">about us</a>
            @if (Auth::check())
            <a id="myEvents" href="{{ url('/myEvents') }}">my Events</a>
            <i class="fa-solid fa-bell"></i>
            <i class="fa-solid fa-user"></i>
            @else
            <a id="myEvents" href="{{ url('/') }}">my Events</a>
            <a id="signIn" href="{{ url('/signIn') }}">signIn</a>
            @endif
        </div>
    </nav>
</header>