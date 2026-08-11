@extends('layouts.app')

@section('title', $property->title)

@section('content')
<section class="inner-banner" style="background:#333;padding:60px 0;color:#fff;">
    <div class="container">
        <h1>{{ $property->title }}</h1>
        <p><i class="fa fa-map-marker"></i> {{ $property->address }}, {{ $property->city }}, {{ $property->country }}</p>
    </div>
</section>

<section class="property-detail" style="padding:60px 0;">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="flexslider main-slider">
                    <ul class="slides">
                        @foreach($property->photos as $photo)
                            <li><img src="{{ asset('storage/'.$photo->path) }}" alt="{{ $property->title }}"></li>
                        @endforeach
                        @if($property->photos->isEmpty())
                            <li><img src="{{ asset('images/img-1.jpg') }}" alt=""></li>
                        @endif
                    </ul>
                </div>

                <ul class="home-in" style="margin-top:20px;">
                    <li><span><i class="fa fa-home"></i> {{ $property->size_acres }} Acres</span></li>
                    <li><span><i class="fa fa-bed"></i> {{ $property->bedrooms }} Bedrooms</span></li>
                    <li><span><i class="fa fa-tty"></i> {{ $property->bathrooms }} Bathrooms</span></li>
                </ul>

                <div style="margin-top:30px;">
                    <span class="tag font-montserrat {{ $property->type?->value }}">{{ strtoupper($property->type?->label()) }}</span>
                    <span class="price font-montserrat" style="font-size:26px;margin-left:15px;">{{ $property->priceFormatted }}</span>
                </div>

                <h3 style="margin-top:30px;">Description</h3>
                <p>{{ $property->description }}</p>

                <h3 style="margin-top:30px;">Address</h3>
                <ul class="list-unstyled">
                    <li><strong>Address:</strong> {{ $property->address }}</li>
                    <li><strong>Zip:</strong> {{ $property->zip }}</li>
                    <li><strong>City:</strong> {{ $property->city }}</li>
                    <li><strong>Country:</strong> {{ $property->country }}</li>
                    @if($property->area)
                        <li><strong>Area:</strong> {{ $property->area }}</li>
                    @endif
                </ul>

                @if(!empty($property->features))
                    <h3 style="margin-top:30px;">Features</h3>
                    <ul class="row">
                        @foreach($property->features as $feature)
                            <li class="col-sm-4"><i class="fa fa-check"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif

                @if(!empty($property->nearby))
                    <h3 style="margin-top:30px;">What's nearby</h3>
                    <ul>
                        @foreach($property->nearby as $item)
                            <li>{{ $item['label'] ?? '' }} — {{ $item['distance_km'] ?? '' }} km</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="col-sm-4">
                @if($property->agent)
                    <div class="agent-block" style="border:1px solid #eee;padding:20px;">
                        <h4>Listing agent</h4>
                        <p><strong>{{ $property->agent->name }}</strong></p>
                        @if($property->agent->phone)
                            <p><i class="fa fa-phone"></i> {{ $property->agent->phone }}</p>
                        @endif
                        <p><i class="fa fa-envelope-o"></i> {{ $property->agent->email }}</p>
                        @if($property->agent->bio)
                            <p>{{ $property->agent->bio }}</p>
                        @endif
                        <p><small>{{ $property->agent->properties()->count() }} listing(s)</small></p>
                    </div>
                @endif

                <div style="margin-top:30px;">
                    <h4>Recent properties</h4>
                    <ul class="recent-come">
                        @foreach($recent as $r)
                            @php $rCover = $r->coverPhoto(); @endphp
                            <li>
                                @if($rCover)
                                    <div class="img-post"><img src="{{ asset('storage/'.$rCover->path) }}" alt=""></div>
                                @endif
                                <div class="text-post">
                                    <a href="{{ route('properties.show', $r) }}">{{ $r->title }}</a>
                                    <span>{{ $r->priceFormatted }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
