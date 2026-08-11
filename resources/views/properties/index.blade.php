@extends('layouts.app')

@section('title', 'Properties')

@section('content')
<section class="inner-banner" style="background:#333;padding:80px 0;color:#fff;text-align:center;">
    <div class="container"><h1>All Properties</h1></div>
</section>

<section class="properties">
    <div class="container">
        @include('partials.flash')
        @include('partials.property-search')

        <div id="propertyMap" style="height:400px;margin-bottom:30px;"></div>

        <ul class="row" style="margin-top:30px;">
            @forelse($properties as $property)
                @include('partials.property-card', ['property' => $property])
            @empty
                <li class="col-sm-12"><p>No properties match your filters.</p></li>
            @endforelse
        </ul>

        <div style="margin-top:20px;">
            {{ $properties->links() }}
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@push('scripts')
<script>window.mapProperties = {!! json_encode($mapProperties) !!};</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WPeI=" crossorigin=""></script>
<script>
$(document).ready(function () {
    if (!document.getElementById('propertyMap')) return;
    var map = L.map('propertyMap').setView([41.6, 21.7], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    (window.mapProperties || []).forEach(function (p) {
        L.marker([p.lat, p.lng])
            .addTo(map)
            .bindPopup(
                '<strong>' + p.title + '</strong><br>' +
                p.price + '<br>' +
                '<a href="' + p.url + '">View property &rarr;</a>'
            );
    });
});
</script>
@endpush
