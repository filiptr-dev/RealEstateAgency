{{--
    Shared login + register markup — ported verbatim from
    realtor/11-Register.html:123–219. The template ships the two forms as
    two side-by-side col-sm-6 panes inside a single <section class="properti-detsil register">,
    NOT as bootstrap tabs (the plan's addendum described tabs, but the source
    file has no nav-tabs markup). Ported 1:1 per Filip's standing rule.

    Both /login and /register include this partial → identical page, same
    URL-independent UX (matches the template's single-page combined form).
    The template's "username" field maps to Breeze's `email` (Breeze auth is
    email-based; do not fork).
--}}

{{-- Sub-banner — ported verbatim from 11-Register.html:124–135 --}}
<div class="sub-banner">
    <div class="overlay">
        <div class="container">
            <h1>login / register</h1>
            <ol class="breadcrumb">
                <li class="pull-left">login / register</li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">login / register</li>
            </ol>
        </div>
    </div>
</div>

{{-- Combined login + register — ported verbatim from 11-Register.html:137–205 --}}
<section class="properti-detsil register">
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-sm-offset-1">
                <div class="row">

                    {{-- LOGIN pane — 11-Register.html:146–168 --}}
                    <div class="col-sm-6">
                        <div class="regi-sec">
                            <span class="regi-tag">LOGIN</span>

                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <ul>
                                    <li>
                                        <label>Email
                                            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                                        </label>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </li>
                                    <li>
                                        <label>Password
                                            <input type="password" name="password" required autocomplete="current-password">
                                        </label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </li>
                                    <li>
                                        <label class="checkbox" style="font-weight:normal;">
                                            <input type="checkbox" name="remember"> Remember me
                                        </label>
                                    </li>
                                    <li>
                                        <button type="submit" class="btn">LOGIN</button>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="forget">Forgot Password?</a>
                                        @endif
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>

                    {{-- REGISTER pane — 11-Register.html:170–205 --}}
                    <div class="col-sm-6">
                        <div class="regi-sec">
                            <span class="regi-tag">register</span>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <ul>
                                    <li>
                                        <label>YOUR NAME
                                            <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
                                        </label>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </li>
                                    <li>
                                        <label>EMAIL
                                            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        </label>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </li>
                                    <li>
                                        <label>PASSWORD
                                            <input type="password" name="password" required autocomplete="new-password">
                                        </label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </li>
                                    <li>
                                        <label>CONFIRM PASSWORD
                                            <input type="password" name="password_confirmation" required autocomplete="new-password">
                                        </label>
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </li>
                                    <li>
                                        <button type="submit" class="btn">Signup</button>
                                        <div class="forget checkbox">
                                            <label>
                                                <input type="checkbox" name="terms" required>
                                                <span>I Agree with terms &amp; Conditions</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
