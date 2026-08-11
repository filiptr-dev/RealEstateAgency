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
