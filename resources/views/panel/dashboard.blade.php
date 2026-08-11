@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome, {{ auth()->user()->name }}</h1>
    <p>Role: <strong>{{ auth()->user()->role?->label() }}</strong></p>

    <div class="row">
        <div class="col-sm-4">
            <div style="background:#fff;padding:20px;border:1px solid #ddd;">
                <h3>{{ $counts['properties'] }}</h3>
                <p>Properties</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div style="background:#fff;padding:20px;border:1px solid #ddd;">
                <h3>{{ $counts['featured'] }}</h3>
                <p>Featured</p>
            </div>
        </div>
    </div>
@endsection
