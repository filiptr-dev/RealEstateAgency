@extends('layouts.app')

@section('title', 'Realtor — Find Your Home')

@section('content')
<div id="banner">
    <div id="homeMap"></div>
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

<section class="services">
    <div class="container">
        <div class="tittle">
            <h3>services we provide</h3>
            <p>From finding the right home to managing your investment — our team supports you at every step.</p>
        </div>
        <ul class="row">
            @php
                $services = [
                    ['img' => 'service-img-1.jpg', 'icon' => 'icon-services-1.png', 'heading' => 'Residential', 'blurb' => 'Homes, apartments, and villas across our top cities — matched to your budget and lifestyle.'],
                    ['img' => 'service-img-2.jpg', 'icon' => 'icon-services-2.png', 'heading' => 'Industrial', 'blurb' => 'Warehouses, workshops, and industrial land for growing businesses and investors.'],
                    ['img' => 'service-img-3.jpg', 'icon' => 'icon-services-3.png', 'heading' => 'Asset management', 'blurb' => 'Hands-off property management for landlords — tenants, maintenance, and reporting handled.'],
                    ['img' => 'service-img-4.jpg', 'icon' => 'icon-services-4.png', 'heading' => 'Financial support', 'blurb' => 'Mortgage guidance and financing partners to help you close on the right terms.'],
                ];
            @endphp
            @foreach($services as $svc)
                <li class="col-sm-3">
                    <section>
                        <img class="img-responsive" src="{{ asset('images/'.$svc['img']) }}" alt="{{ $svc['heading'] }}">
                        <div class="icon"><img src="{{ asset('images/'.$svc['icon']) }}" alt=""></div>
                        <div class="ser-hover">
                            <p>{{ $svc['blurb'] }} <a href="{{ route('services') }}" class="read-more">Read more <i class="fa fa-angle-double-right"></i></a></p>
                        </div>
                        <a href="{{ route('services') }}" class="heading">{{ $svc['heading'] }}</a>
                    </section>
                </li>
            @endforeach
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

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@push('scripts')
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
    var markers = [];
    (window.homeMapProperties || []).forEach(function (p) {
        markers.push(L.marker([p.lat, p.lng])
            .addTo(map)
            .bindPopup(
                '<strong>' + p.title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</strong><br>' +
                p.price + '<br>' +
                '<a href="' + p.url + '">View property &rarr;</a>'
            ));
    });
    if (markers.length) {
        map.fitBounds(L.featureGroup(markers).getBounds(), {padding: [30, 30], maxZoom: 14});
    }
});
</script>
@endpush
