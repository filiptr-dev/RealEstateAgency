@extends('layouts.app')

@section('title', 'Our agents')

@section('content')
{{-- Sub-banner — ported verbatim from 06-Our-Agents.html:124–135 --}}
<div class="sub-banner">
    <div class="overlay">
        <div class="container">
            <h1>OUR AGENTS</h1>
            <ol class="breadcrumb">
                <li class="pull-left">our agents</li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">our agents</li>
            </ol>
        </div>
    </div>
</div>

{{-- Team — template markup from 06-Our-Agents.html:139–246, driven by real $agents.
     Note: the template file has this <section id="team"> block duplicated (lines
     269–376 repeat it verbatim, which is a template authoring bug); we render it
     once, driven by the real seeded agents. --}}
<section id="team">
    <div class="container">
        <div class="tittle"><img src="{{ asset('images/head-top.png') }}" alt="">
            <h3>our great agents</h3>
            <p>Meet the people who will help you find your next home. Every agent listed below is a real member of our team.</p>
        </div>

        @if($agents->isEmpty())
            <p class="text-center">No agents to show yet.</p>
        @else
            <div class="row">
                @foreach($agents->chunk(2) as $pair)
                    <div class="col-md-6">
                        <ul class="row">
                            @foreach($pair as $agent)
                                @php
                                    $photo = $agent->photo_path
                                        ? (str_starts_with($agent->photo_path, 'http') ? $agent->photo_path : asset($agent->photo_path))
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

{{-- Call us — ported verbatim from 06-Our-Agents.html:250–265 --}}
<section class="call-us">
    <div class="overlay">
        <div class="container">
            <ul class="row">
                <li class="col-sm-6">
                    <h4>Do you want to sell your property?</h4>
                    <h6>Call us and list your property here</h6>
                </li>
                <li class="col-sm-4">
                    <h1>+01 123 456 78</h1>
                </li>
                <li class="col-sm-2 no-padding"><a href="{{ route('contact.create') }}" class="btn">just contact us</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
