@php
    $footerRecent = \App\Models\Property::published()->with('photos')->latest('published_at')->limit(3)->get();
@endphp
<footer>
    <div class="container">

        {{-- Newsletter — ported verbatim from 02-Homepage-02.html:736–746, wired to no-op --}}
        <div class="subcribe">
            <div class="col-sm-5 no-padding">
                <h4>Subscribe to our newsletter</h4>
            </div>
            <div class="col-sm-7 no-padding">
                <form onsubmit="return false;">
                    <input class="font-montserrat" type="email" placeholder="E-mail Address" value="" required>
                    <button type="submit" class="btn"> Subscribe</button>
                </form>
            </div>
        </div>

        <ul class="row">
            <li class="col-sm-3">
                <h5><a href="{{ route('about') }}" style="color:inherit;">About us</a></h5><hr>
                <p>Realtor helps you find the perfect place, whether you're renting or buying. Browse our
                   curated listings and reach out to an agent when you're ready to visit.</p>
                <ul class="social_icons">
                    <li class="facebook"><a href="#."><i class="fa fa-facebook"></i></a></li>
                    <li class="twitter"><a href="#."><i class="fa fa-twitter"></i></a></li>
                    <li class="linkedin"><a href="#."><i class="fa fa-linkedin"></i></a></li>
                    <li class="tumblr"><a href="#."><i class="fa fa-tumblr"></i></a></li>
                </ul>
            </li>

            {{-- Latest tweets — ported verbatim from 02-Homepage-02.html:766–778 (static in the template too) --}}
            <li class="col-sm-3">
                <h5>latest tweets</h5><hr>
                <ul class="tweet">
                    <li>
                        <p>Realtor is one of the excellent realestatetheme <a href="#.">http://realtorrealestae</a> one of the excellent realestatetheme </p>
                        <span>1 hours ago</span>
                    </li>
                    <li>
                        <p>Realtor is one of the excellent realestatetheme <a href="#.">http://realtorrealestae</a> one of the excellent realestatetheme </p>
                        <span>1 hours ago</span>
                    </li>
                </ul>
            </li>

            <li class="col-sm-3">
                <h5>Recent properties</h5><hr>
                <ul class="recent-come">
                    @foreach($footerRecent as $recent)
                        @php $cover = $recent->coverPhoto(); @endphp
                        <li>
                            @if($cover)
                                <div class="img-post"><img src="{{ $cover->url }}" alt=""></div>
                            @endif
                            <div class="text-post">
                                <a href="{{ route('properties.show', $recent) }}">{{ $recent->title }}</a>
                                <span>{{ $recent->priceFormatted }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li class="col-sm-3">
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
