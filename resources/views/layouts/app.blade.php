<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
    /* Batch 4 — uniform photo sizing */
    /* Property cards: landscape crop */
    .properties li section .img img,
    .properties li section > img.img-responsive {
        height: 220px;
        width: 100%;
        object-fit: cover;
        display: block;
    }
    /* Service section card images */
    .services li section > img.img-responsive {
        height: 200px;
        width: 100%;
        object-fit: cover;
        display: block;
    }
    /* Recent-properties sidebar thumbs */
    ul.recent-come li .img-post img {
        height: 64px;
        width: 80px;
        object-fit: cover;
    }
    /* Team/agent photos */
    #team .team > img.img-responsive,
    .team > img.img-responsive {
        height: 260px;
        width: 100%;
        object-fit: cover;
        display: block;
    }
    </style>
</head>
<body>
<div id="wrap" class="home-1">
    @include('partials.header')

    @yield('content')

    @include('partials.footer')
</div>

<script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.js') }}"></script>
<script src="{{ asset('js/jquery.flexslider-min.js') }}"></script>
<script src="{{ asset('js/jquery.sticky.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
