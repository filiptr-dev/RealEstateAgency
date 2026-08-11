@php
    $footerRecent = \App\Models\Property::published()->with('photos')->latest('published_at')->limit(3)->get();
@endphp
<footer>
    <div class="container">
        <ul class="row">
            <li class="col-sm-4">
                <h5>About us</h5><hr>
                <p>Realtor helps you find the perfect place, whether you're renting or buying. Browse our
                   curated listings and reach out to an agent when you're ready to visit.</p>
                <ul class="social_icons">
                    <li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </li>
            <li class="col-sm-4">
                <h5>Recent properties</h5><hr>
                <ul class="recent-come">
                    @foreach($footerRecent as $recent)
                        @php $cover = $recent->coverPhoto(); @endphp
                        <li>
                            @if($cover)
                                <div class="img-post"><img src="{{ asset('storage/'.$cover->path) }}" alt=""></div>
                            @endif
                            <div class="text-post">
                                <a href="{{ route('properties.show', $recent) }}">{{ $recent->title }}</a>
                                <span>{{ $recent->priceFormatted }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="col-sm-4">
                <h5>Contact</h5><hr>
                <div class="loc-info">
                    <p><i class="fa fa-map-marker"></i> 09 Design Street, Downtown, Sydney</p>
                    <p><i class="fa fa-phone"></i> +01 123 456 78</p>
                    <p><i class="fa fa-envelope-o"></i> info@realtor.example</p>
                </div>
            </li>
        </ul>
    </div>
</footer>
<div class="rights">
    <div class="container">
        <p class="font-montserrat">&copy; {{ date('Y') }} Realtor. All rights reserved</p>
    </div>
</div>
