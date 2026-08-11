@extends('layouts.app')

@section('title', 'Services')

@section('content')
{{-- Sub-banner — ported verbatim from 05-Services.html:124–135 --}}
<div class="sub-banner">
    <div class="overlay">
        <div class="container">
            <h1>services</h1>
            <ol class="breadcrumb">
                <li class="pull-left">services</li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">services</li>
            </ol>
        </div>
    </div>
</div>

{{-- Services — ported verbatim from 05-Services.html:137–205 --}}
<section class="services">
    <div class="container">
        <div class="tittle"><img src="{{ asset('images/head-top.png') }}" alt="">
            <h3>services we provide</h3>
            <p>This time there's no stopping us. Straightnin' the curves. Flatnin' the hills Someday the mountain might get ‘em but the law never will. The weather started getting rough - the tiny ship was tossed.</p>
        </div>
        <ul class="row">
            <li class="col-sm-3">
                <section>
                    <img class="img-responsive" src="{{ asset('images/service-img-1.jpg') }}" alt="">
                    <div class="icon"><img src="{{ asset('images/icon-services-1.png') }}" alt=""></div>
                    <div class="ser-hover">
                        <p>And when the odds are against him and their dangers work to do. You bet your life Speed Racer <a href="#." class="read-more">Read more <i class="fa fa-angle-double-right"></i></a></p>
                    </div>
                    <a href="#." class="heading">Residential</a>
                </section>
            </li>
            <li class="col-sm-3">
                <section>
                    <img class="img-responsive" src="{{ asset('images/service-img-2.jpg') }}" alt="">
                    <div class="icon"><img src="{{ asset('images/icon-services-2.png') }}" alt=""></div>
                    <div class="ser-hover">
                        <p>And when the odds are against him and their dangers work to do. You bet your life Speed Racer <a href="#." class="read-more">Read more <i class="fa fa-angle-double-right"></i></a></p>
                    </div>
                    <a href="#." class="heading">industrial</a>
                </section>
            </li>
            <li class="col-sm-3">
                <section>
                    <img class="img-responsive" src="{{ asset('images/service-img-3.jpg') }}" alt="">
                    <div class="icon"><img src="{{ asset('images/icon-services-3.png') }}" alt=""></div>
                    <div class="ser-hover">
                        <p>And when the odds are against him and their dangers work to do. You bet your life Speed Racer <a href="#." class="read-more">Read more <i class="fa fa-angle-double-right"></i></a></p>
                    </div>
                    <a href="#." class="heading">Asset management</a>
                </section>
            </li>
            <li class="col-sm-3">
                <section>
                    <img class="img-responsive" src="{{ asset('images/service-img-4.jpg') }}" alt="">
                    <div class="icon"><img src="{{ asset('images/icon-services-4.png') }}" alt=""></div>
                    <div class="ser-hover">
                        <p>And when the odds are against him and their dangers work to do. You bet your life Speed Racer <a href="#." class="read-more">Read more <i class="fa fa-angle-double-right"></i></a></p>
                    </div>
                    <a href="#." class="heading">financial support</a>
                </section>
            </li>
        </ul>
    </div>
</section>

{{-- Properties — template markup from 05-Services.html:207–289, driven by real $latest --}}
<section class="properties gray-bg">
    <div class="container">
        <div class="tittle"><img src="{{ asset('images/head-top.png') }}" alt="">
            <h3>new properties list</h3>
            <p>This time there's no stopping us. Straightnin' the curves. Flatnin' the hills Someday the mountain might get ‘em but the law never will. The weather started getting rough - the tiny ship was tossed.</p>
        </div>

        @if($latest->isEmpty())
            <p class="text-center">No properties published yet.</p>
        @else
            <ul class="row">
                @foreach($latest as $property)
                    @include('partials.property-card', ['property' => $property])
                @endforeach
            </ul>
            <div class="text-center"><a href="{{ route('properties.index') }}" class="load-more font-montserrat">Load More</a></div>
        @endif
    </div>
</section>

{{-- Pricing — ported verbatim from 05-Services.html:291–367 --}}
<section class="pricing">
    <div class="container">
        <div class="tittle"><img src="{{ asset('images/head-top.png') }}" alt="">
            <h3>our membership plans</h3>
            <p>This time there's no stopping us. Straightnin' the curves. Flatnin' the hills Someday the mountain might get ‘em but the law never will. The weather started getting rough - the tiny ship was tossed.</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <ul class="row">
                    <li class="col-sm-6">
                        <h6>Standard</h6>
                        <div class="price-head font-montserrat"><span class="curency">$</span>29<span class="month">/ mo</span></div>
                        <div class="p-details">
                            <p>3 Month Membership</p>
                            <p class="gry-bg"> 1 Property Allowed</p>
                            <p> No Featured Property</p>
                            <p class="gry-bg"> 24/7 Support</p>
                            <p> 2 Free Listing</p>
                            <a href="#" class="font-montserrat">Get Started Now <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </li>
                    <li class="col-sm-6">
                        <h6>business</h6>
                        <div class="price-head font-montserrat"><span class="curency">$</span>49<span class="month">/ mo</span></div>
                        <div class="p-details">
                            <p>3 Month Membership</p>
                            <p class="gry-bg"> 1 Property Allowed</p>
                            <p> No Featured Property</p>
                            <p class="gry-bg"> 24/7 Support</p>
                            <p> 2 Free Listing</p>
                            <a href="#" class="font-montserrat">Get Started Now <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="row">
                    <li class="col-sm-6 papular">
                        <h6>premium</h6>
                        <div class="price-head font-montserrat"><span class="curency">$</span>79<span class="month">/ mo</span></div>
                        <div class="p-details">
                            <p>3 Month Membership</p>
                            <p class="gry-bg"> 1 Property Allowed</p>
                            <p> No Featured Property</p>
                            <p class="gry-bg"> 24/7 Support</p>
                            <p> 2 Free Listing</p>
                            <a href="#" class="font-montserrat">Get Started Now <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </li>
                    <li class="col-sm-6">
                        <h6>ultimate</h6>
                        <div class="price-head font-montserrat"><span class="curency">$</span>99<span class="month">/ mo</span></div>
                        <div class="p-details">
                            <p>3 Month Membership</p>
                            <p class="gry-bg"> 1 Property Allowed</p>
                            <p> No Featured Property</p>
                            <p class="gry-bg"> 24/7 Support</p>
                            <p> 2 Free Listing</p>
                            <a href="#" class="font-montserrat">Get Started Now <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Testimonials — ported verbatim from 05-Services.html:373–404 --}}
<section id="testimonials">
    <div class="container">
        <div class="tittle">
            <h3>what people say about us</h3>
        </div>
        <div class="testi">
            <div class="testi-slide">
                <div class="item">
                    <div class="avatar"><img src="{{ asset('images/avatar-1.jpg') }}" alt=""></div>
                    <p>These days are all Happy and Free. These days are all share them with me oh baby. The year is 1987 and NASA launches the last of Americas deep space probes. Flying If you have a problem if no one else can help and if you can find them maybe you can hire a prayer.</p>
                    <h5>mitchell warner</h5>
                    <span>Sydney, Australia</span>
                </div>
                <div class="item">
                    <div class="avatar"><img src="{{ asset('images/avatar-1.jpg') }}" alt=""></div>
                    <p>These days are all Happy and Free. These days are all share them with me oh baby. The year is 1987 and NASA launches the last of Americas deep space probes. Flying If you have a problem if no one else can help and if you can find them maybe you can hire a prayer.</p>
                    <h5>mitchell warner</h5>
                    <span>Sydney, Australia</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
