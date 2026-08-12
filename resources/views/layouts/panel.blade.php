<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — {{ config('app.name') }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Panel-only styles. Namespaced under body.panel-body so nothing leaks to the public site. */
        body.panel-body {
            background: #f8f8f8;
            font-family: 'Lato', sans-serif;
            color: #444;
            margin: 0;
        }
        body.panel-body a { text-decoration: none; }

        /* Top bar */
        .panel-topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 50px;
            background: #222222;
            color: #fff;
            z-index: 100;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        .panel-topbar .panel-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: #fff;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .panel-topbar .panel-topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
            font-family: 'Lato', sans-serif;
            font-size: 13px;
        }
        .panel-topbar .panel-topbar-right span,
        .panel-topbar .panel-topbar-right a { color: #fff; }
        .panel-topbar .panel-topbar-right a:hover { color: #ff5722; }
        .panel-topbar .panel-logout-btn {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-family: 'Lato', sans-serif;
            font-size: 13px;
            padding: 0;
        }
        .panel-topbar .panel-logout-btn:hover { color: #ff5722; }

        /* Sidebar */
        .panel-sidebar {
            position: fixed;
            top: 50px;
            left: 0;
            width: 220px;
            height: calc(100vh - 50px);
            background: #2b2b2b;
            overflow-y: auto;
            padding: 20px 0;
        }
        .panel-sidebar .panel-nav-label {
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 15px 20px 8px;
            display: block;
        }
        .panel-sidebar .panel-nav-item {
            display: block;
            padding: 10px 20px;
            color: #9c9c9c;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
        }
        .panel-sidebar .panel-nav-item i {
            width: 18px;
            margin-right: 10px;
            text-align: center;
        }
        .panel-sidebar .panel-nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.03);
        }
        .panel-sidebar .panel-nav-item.active {
            border-left: 3px solid #ff5722;
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        /* Main content area */
        .panel-main {
            margin-left: 220px;
            margin-top: 50px;
            padding: 30px;
            min-height: calc(100vh - 50px);
        }

        /* Card pattern */
        .panel-card {
            background: #ffffff;
            border: 1px solid #ececec;
            border-top: 2px solid #ff5722;
            padding: 25px;
            margin-bottom: 20px;
        }
        .panel-card-flush { padding: 0; }
        .panel-card-flush .panel-card-body { padding: 20px 25px; }
        .panel-card-flush .panel-card-head {
            padding: 15px 25px;
            border-bottom: 1px solid #ececec;
        }
        .panel-card-head h5 {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            text-transform: uppercase;
            color: #222;
            letter-spacing: 1px;
        }

        /* Page heading */
        .panel-section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;
            text-transform: uppercase;
            color: #222;
            letter-spacing: 1px;
            margin: 0 0 25px 0;
            font-weight: 600;
        }
        .panel-section-title .panel-title-actions { float: right; }

        /* Stat card */
        .panel-stat-card {
            background: #fff;
            border: 1px solid #ececec;
            border-top: 2px solid #ff5722;
            padding: 25px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        .panel-stat-card .panel-stat-number {
            font-family: 'Montserrat', sans-serif;
            font-size: 36px;
            font-weight: 700;
            color: #222;
            line-height: 1;
        }
        .panel-stat-card .panel-stat-label {
            font-family: 'Lato', sans-serif;
            font-size: 13px;
            text-transform: uppercase;
            color: #777;
            letter-spacing: 1px;
            margin-top: 8px;
        }
        .panel-stat-card .panel-stat-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 28px;
            color: #ececec;
        }

        /* Tables */
        .panel-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        .panel-table thead th {
            background: #f2f2f2;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            color: #444;
            letter-spacing: 1px;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ececec;
        }
        .panel-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #ececec;
            font-family: 'Lato', sans-serif;
            font-size: 13px;
            color: #444;
        }
        .panel-table tbody tr:hover { background: #fafafa; }
        .panel-table a.panel-action-edit {
            color: #4caf50;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-right: 12px;
        }
        .panel-table .panel-action-delete {
            background: none;
            border: none;
            color: #ff5722;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            padding: 0;
        }
        .panel-table a.panel-action-view {
            color: #4caf50;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .panel-table a.panel-cell-title {
            color: #222;
            font-weight: 600;
        }
        .panel-table a.panel-cell-title:hover { color: #ff5722; }

        /* Badges */
        .panel-badge {
            display: inline-block;
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 2px 8px;
            border-radius: 0;
        }
        .panel-badge-unread { background: #ff5722; color: #fff; }
        .panel-badge-read { background: #f2f2f2; color: #777; }
        .panel-badge-role-admin { background: #222; color: #fff; }
        .panel-badge-role-agent { background: #4caf50; color: #fff; }
        .panel-badge-role-user { background: #f2f2f2; color: #777; }

        /* "View all" links */
        .panel-view-all {
            color: #ff5722;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-top: 15px;
        }
        .panel-view-all:hover { color: #4caf50; }

        /* Recent inquiries list */
        .panel-inquiry-item {
            padding: 12px 0;
            border-bottom: 1px solid #ececec;
        }
        .panel-inquiry-item:last-child { border-bottom: none; }
        .panel-inquiry-item .panel-inquiry-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            color: #222;
            font-weight: 600;
        }
        .panel-inquiry-item .panel-inquiry-body {
            font-family: 'Lato', sans-serif;
            font-size: 12px;
            color: #666;
            margin: 4px 0;
        }
        .panel-inquiry-item .panel-inquiry-date {
            font-family: 'Lato', sans-serif;
            font-size: 11px;
            color: #999;
        }

        /* Buttons */
        .panel-btn {
            background: #4caf50;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 18px;
            border: none;
            border-radius: 0;
            display: inline-block;
            cursor: pointer;
        }
        .panel-btn:hover { background: #ff5722; color: #fff; }

        /* Flash overrides */
        .panel-main .alert { padding: 12px 15px; margin-bottom: 20px; border-radius: 0; font-family: 'Lato', sans-serif; font-size: 13px; }
        .panel-main .alert-success { background: #dff0d8; color: #3c763d; border: 1px solid #d0e9c6; }
        .panel-main .alert-danger { background: #f2dede; color: #a94442; border: 1px solid #ebcccc; }
    </style>
</head>
<body class="panel-body">
@php
    $currentRoute = request()->route()?->getName() ?? '';
    $navActive = fn (string $prefix) => str_starts_with($currentRoute, $prefix) ? 'active' : '';
@endphp

<header class="panel-topbar">
    <a href="{{ route('panel.dashboard') }}" class="panel-brand">REALTOR</a>
    <div class="panel-topbar-right">
        <span>{{ auth()->user()->name }}</span>
        <a href="{{ route('home') }}">← Public site</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0;">
            @csrf
            <button type="submit" class="panel-logout-btn">Logout</button>
        </form>
    </div>
</header>

<aside class="panel-sidebar">
    <span class="panel-nav-label">Overview</span>
    <a href="{{ route('panel.dashboard') }}" class="panel-nav-item {{ $currentRoute === 'panel.dashboard' ? 'active' : '' }}">
        <i class="fa fa-tachometer"></i>Dashboard
    </a>

    <span class="panel-nav-label">Manage</span>
    <a href="{{ route('panel.properties.index') }}" class="panel-nav-item {{ $navActive('panel.properties') }}">
        <i class="fa fa-home"></i>Properties
    </a>
    <a href="{{ route('panel.inquiries.index') }}" class="panel-nav-item {{ $navActive('panel.inquiries') }}">
        <i class="fa fa-envelope-o"></i>Inquiries
    </a>
    <a href="{{ route('panel.blog.index') }}" class="panel-nav-item {{ $navActive('panel.blog') }}">
        <i class="fa fa-pencil-square-o"></i>Blog Posts
    </a>

    @if(auth()->user()->isAdmin())
        <span class="panel-nav-label">Admin</span>
        <a href="{{ route('panel.admin.users.index') }}" class="panel-nav-item {{ $navActive('panel.admin.users') }}">
            <i class="fa fa-users"></i>Agents & Users
        </a>
    @endif
</aside>

<main class="panel-main">
    @include('partials.flash')
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
