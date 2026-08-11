@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
{{-- Sub-banner — ported verbatim from 12-404.html:124–135 --}}
<div class="sub-banner">
    <div class="overlay">
        <div class="container">
            <h1>404</h1>
            <ol class="breadcrumb">
                <li class="pull-left">404</li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">404</li>
            </ol>
        </div>
    </div>
</div>

{{-- 404 body — ported verbatim from 12-404.html:137–149; CTAs wired to real routes. --}}
<section class="error-page">
    <div class="container">
        <div class="row">
            <div class="col-sm-7 text-center">
                <span class="not-found font-montserrat">page not found</span>
                <span class="head-404 font-montserrat">404</span>
                <h4>Page doesn’t exist or other error occured. Go to our
                    <a href="{{ route('home') }}" class="font-montserrat">HOMEPAGE</a>
                    or go back to
                    <a href="javascript:history.back()" class="font-montserrat">PREVIOUS PAGE</a>
                </h4>
            </div>
            <div class="col-sm-5"><img class="img-responsive" src="{{ asset('images/404-img.png') }}" alt=""></div>
        </div>
    </div>
</section>
@endsection
