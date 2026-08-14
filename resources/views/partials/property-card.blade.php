@php
    $cover = $property->coverPhoto();
    $typeClass = $property->type?->value === 'sale' ? 'sale' : 'rent';
    $typeLabel = $property->type?->label();
@endphp
<li class="col-sm-4">
    <span class="tag font-montserrat {{ $typeClass }}">{{ strtoupper($typeLabel) }}</span>
    <section>
        <div class="img">
            @if($cover)
                <img class="img-responsive" src="{{ $cover->url }}" alt="{{ $property->title }}">
            @else
                <img class="img-responsive" src="{{ asset('images/img-1.jpg') }}" alt="{{ $property->title }}">
            @endif
            <div class="over-proper">
                <a href="{{ route('properties.show', $property) }}" class="btn font-montserrat">more details</a>
            </div>
        </div>
        <ul class="home-in">
            <li><span><i class="fa fa-home"></i> {{ $property->size_acres }} Acres</span></li>
            <li><span><i class="fa fa-bed"></i> {{ $property->bedrooms }} Bedrooms</span></li>
            <li><span><i class="fa fa-tty"></i> {{ $property->bathrooms }} Bathrooms</span></li>
        </ul>
        <div class="detail-sec">
            <a href="{{ route('properties.show', $property) }}" class="font-montserrat">{{ $property->title }}</a>
            <span class="locate"><i class="fa fa-map-marker"></i> {{ $property->city }}, {{ $property->country }}</span>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($property->description), 100) }}</p>
            <div class="share-p">
                <span class="price font-montserrat">{{ $property->priceFormatted }}</span>
            </div>
        </div>
    </section>
</li>
