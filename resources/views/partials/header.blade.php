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
    </div>
</div>

<header class="sticky">
    <div class="container">
        <div class="logo"><a href="{{ route('home') }}"><strong style="font-size:24px;color:#fff;">Realtor</strong></a></div>
        <nav>
            <ul class="ownmenu">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                <li class="{{ request()->routeIs('properties.*') ? 'active' : '' }}"><a href="{{ route('properties.index') }}">Properties</a></li>
                <li class="{{ request()->routeIs('contact.*') ? 'active' : '' }}"><a href="{{ route('contact.create') }}">Contact us</a></li>
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                        <li><a href="{{ route('panel.dashboard') }}">Panel</a></li>
                    @endif
                @endauth
            </ul>
        </nav>
    </div>
</header>
