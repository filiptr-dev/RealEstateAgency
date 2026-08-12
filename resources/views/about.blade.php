@extends('layouts.app')

@section('title', 'About us')

@section('content')
{{-- Sub-banner — ported verbatim from 04-About-Us.html:124–135 --}}
<div class="sub-banner">
    <div class="overlay">
        <div class="container">
            <h1>ABOUT US</h1>
            <ol class="breadcrumb">
                <li class="pull-left">ABOUT us</li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">ABOUT Us</li>
            </ol>
        </div>
    </div>
</div>

{{-- What We Do — ported verbatim from 04-About-Us.html:139–179 --}}
<section class="what-we-do">
    <div class="container">
        <div class="tittle"><img src="{{ asset('images/head-top.png') }}" alt="">
            <h3>who we are</h3>
            <p>This time there's no stopping us. Straightnin' the curves. Flatnin' the hills Someday the mountain might get 'em but the law never will. The weather started getting rough - the tiny ship was tossed.</p>
        </div>
        <div role="tabpanel">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#what-we" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-bullhorn"></i> <span class="font-montserrat">what we do</span></a></li>
                <li role="presentation"><a href="#mission" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-rocket"></i><span class="font-montserrat">our mission</span></a></li>
                <li role="presentation"><a href="#vision" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-lightbulb-o"></i><span class="font-montserrat">our vision</span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane animated-6s flipInX active" id="what-we">
                    <h4>make your family happy</h4>
                    <p>The mate was a mighty sailin' man the Skipper brave and sure. Five passengers set sail that day for a three hour tour a three hour tour. The first mate and his Skipper too will do their very best to make the others comfortable in their tropic island nest. If you have a problem if no one else can help and if you can find</p>
                </div>
                <div role="tabpanel" class="tab-pane animated-6s flipInX" id="mission">
                    <h4>make your family happy</h4>
                    <p>The mate was a mighty sailin' man the Skipper brave and sure. Five passengers set sail that day for a three hour tour a three hour tour. The first mate and his Skipper too will do their very best to make the others comfortable in their tropic island nest. If you have a problem if no one else can help and if you can find</p>
                </div>
                <div role="tabpanel" class="tab-pane animated-6s flipInX" id="vision">
                    <h4>make your family happy</h4>
                    <p>The mate was a mighty sailin' man the Skipper brave and sure. Five passengers set sail that day for a three hour tour a three hour tour. The first mate and his Skipper too will do their very best to make the others comfortable in their tropic island nest. If you have a problem if no one else can help and if you can find</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Team — template markup from 04-About-Us.html:182–289, driven by real $agents --}}
<section id="team">
    <div class="container">
        <div class="tittle"><img src="{{ asset('images/head-top.png') }}" alt="">
            <h3>our great agents</h3>
            <p>Meet the people who will help you find your next home. Every agent listed below is a real member of our team.</p>
        </div>

        @if($agents->isEmpty())
            <p class="text-center">No agents to show yet.</p>
        @else
            {{-- Two rows of two, matching the template's 2×(col-md-6 → 2×col-sm-6) grid. --}}
            <div class="row">
            @foreach($agents->chunk(2) as $pair)
                <div class="col-md-6">
                    <ul class="row">
                        @foreach($pair as $agent)
                            @php
                                $photo = $agent->photo_path
                                    ? asset($agent->photo_path)
                                    : asset('images/agent-1.jpg');
                            @endphp
                            <li class="col-sm-6">
                                <div class="team">
                                    <img class="img-responsive" src="{{ $photo }}" alt="{{ $agent->name }}">
                                    <div class="team-over">
                                        <ul class="social_icons animated-6s fadeInUp">
                                            <li class="facebook"><a href="#."><i class="fa fa-facebook"></i></a></li>
                                            <li class="twitter"><a href="#."><i class="fa fa-twitter"></i></a></li>
                                            <li class="googleplus"><a href="#."><i class="fa fa-google-plus"></i></a></li>
                                            <li class="linkedin"><a href="#."><i class="fa fa-linkedin"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="team-detail">
                                        <h6>{{ $agent->name }}</h6>
                                        <p>{{ $agent->role->label() }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
