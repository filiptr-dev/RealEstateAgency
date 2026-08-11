<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — {{ config('app.name') }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        body { background: #f5f5f5; font-family: sans-serif; }
        .panel-header { background:#222;color:#fff;padding:15px 0; }
        .panel-header a { color:#fff; margin-right:20px; text-decoration:none; }
        .panel-body { padding:30px 0; }
        table.table th, table.table td { padding:8px; border-bottom:1px solid #ddd; }
        .alert { padding:10px; margin:10px 0; }
        .alert-success { background:#dff0d8; color:#3c763d; }
        .alert-danger { background:#f2dede; color:#a94442; }
    </style>
</head>
<body>
<header class="panel-header">
    <div class="container">
        <strong style="margin-right:30px;">Realtor Panel</strong>
        <a href="{{ route('panel.dashboard') }}">Dashboard</a>
        <a href="{{ route('panel.properties.index') }}">Properties</a>
        <a href="{{ route('panel.inquiries.index') }}">Inquiries</a>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('panel.admin.users.index') }}">Users</a>
            @endif
        @endauth
        <a href="{{ route('home') }}" style="float:right;">← Public site</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline-block;float:right;margin-right:20px;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#fff;cursor:pointer;">Logout</button>
        </form>
    </div>
</header>

<main class="panel-body">
    <div class="container">
        @include('partials.flash')
        @yield('content')
    </div>
</main>
</body>
</html>
