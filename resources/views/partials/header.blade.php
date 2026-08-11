<div class="top-bar">
    <div class="container">
        <ul class="left-bar-side">
            <li><p><i class="fa fa-phone"></i> Call Us Now : +01 123 456 78 </p><span>|</span></li>
            <li><p><i class="fa fa-envelope-o"></i> info@realtor.example </p><span>|</span></li>
            <li>
                @auth
                    <p><i class="fa fa-user"></i>
                        <a href="{{ route('dashboard') }}">{{ auth()->user()->name }}</a>
                        &middot;
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </p>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                @else
                    <p><i class="fa fa-lock"></i>
                        <a href="{{ route('login') }}">Login</a>
                        /
                        <a href="{{ route('register') }}">Register</a>
                    </p>
                @endauth
                <span>|</span>
            </li>
        </ul>
        {{-- Right-bar social icons — ported verbatim from 02-Homepage-02.html:50–55 --}}
        <ul class="right-bar-side social_icons">
            <li class="facebook"><a href="#."><i class="fa fa-facebook"></i></a></li>
            <li class="twitter"><a href="#."><i class="fa fa-twitter"></i></a></li>
            <li class="linkedin"><a href="#."><i class="fa fa-linkedin"></i></a></li>
            <li class="tumblr"><a href="#."><i class="fa fa-tumblr"></i></a></li>
        </ul>
    </div>
</div>

<header class="sticky">
    <div class="container">
        <div class="logo"><a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="Realtor"></a></div>
        <nav>
            <ul class="ownmenu">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                <li class="{{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}">About</a></li>
                <li class="{{ request()->routeIs('services') ? 'active' : '' }}"><a href="{{ route('services') }}">Services</a></li>
                <li class="{{ request()->routeIs('properties.*') ? 'active' : '' }}"><a href="{{ route('properties.index') }}">Properties</a></li>
                <li class="{{ request()->routeIs('agents') ? 'active' : '' }}"><a href="{{ route('agents') }}">Our Agents</a></li>
                <li class="{{ request()->routeIs('blog.*') ? 'active' : '' }}"><a href="{{ route('blog.index') }}">Blog</a></li>
                <li class="{{ request()->routeIs('contact.*') ? 'active' : '' }}"><a href="{{ route('contact.create') }}">Contact us</a></li>
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                        <li><a href="{{ route('panel.dashboard') }}">Panel</a></li>
                    @endif
                @endauth
            </ul>
            {{-- Search icon — ported verbatim from 02-Homepage-02.html:117 --}}
            <div class="sub-nav-co"><a href="#."><i class="fa fa-search"></i></a></div>
        </nav>
    </div>
</header>
