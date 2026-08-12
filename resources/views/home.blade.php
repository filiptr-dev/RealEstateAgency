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

@if($mapProperties->isNotEmpty())
<section style="padding:40px 0 0;">
    <div class="container">
        <div class="tittle">
            <h3>Properties on the map</h3>
            <p>Browse our portfolio geographically.</p>
        </div>
        <div id="homeMap" style="height:450px;margin-bottom:30px;"></div>
    </div>
</section>
@endif

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

@push('styles')
@if($mapProperties->isNotEmpty())
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endif
@endpush

@push('scripts')
@if($mapProperties->isNotEmpty())
<script>window.homeMapProperties = {!! json_encode($mapProperties, JSON_HEX_TAG) !!};</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WPeI=" crossorigin=""></script>
<script>
$(document).ready(function () {
    if (!document.getElementById('homeMap')) return;
    var map = L.map('homeMap').setView([41.6, 21.7], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    (window.homeMapProperties || []).forEach(function (p) {
        L.marker([p.lat, p.lng])
            .addTo(map)
            .bindPopup(
                '<strong>' + p.title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</strong><br>' +
                p.price + '<br>' +
                '<a href="' + p.url + '">View property &rarr;</a>'
            );
    });
});
</script>
@endif
@endpush
