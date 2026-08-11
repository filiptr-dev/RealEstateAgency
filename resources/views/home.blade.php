@extends('layouts.app')

@section('title', 'Realtor — Find Your Home')

@section('content')
<div id="banner" style="background:#333;">
    <img src="{{ asset('images/slider-img-1.jpg') }}" alt="" style="width:100%;display:block;opacity:0.8;">
    <div class="finder">
        <div class="container">
            <h1>Welcome to Realtor</h1>
            @include('partials.property-search')
        </div>
    </div>
</div>

<section class="properties">
    <div class="container">
        <div class="tittle">
            <h3>Featured properties</h3>
            <p>Hand-picked homes across our top cities.</p>
        </div>
        <ul class="row">
            @forelse($featured as $property)
                @include('partials.property-card', ['property' => $property])
            @empty
                <li class="col-sm-12"><p>No featured properties yet.</p></li>
            @endforelse
        </ul>
    </div>
</section>

<section class="properties" style="padding-top:0;">
    <div class="container">
        <div class="tittle">
            <h3>Latest properties</h3>
            <p>Recently added listings from our agents.</p>
        </div>
        <ul class="row">
            @forelse($latest as $property)
                @include('partials.property-card', ['property' => $property])
            @empty
                <li class="col-sm-12"><p>No properties yet.</p></li>
            @endforelse
        </ul>
    </div>
</section>

<section class="call-us">
    <div class="overlay">
        <div class="container">
            <ul class="row">
                <li class="col-sm-6"><h4>Do you want to sell your property?</h4><h6>Call us and list it here.</h6></li>
                <li class="col-sm-4"><h1>+01 123 456 78</h1></li>
                <li class="col-sm-2 no-padding"><a href="{{ route('contact.create') }}" class="btn">Contact us</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
