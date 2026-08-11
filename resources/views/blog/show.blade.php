@extends('layouts.app')

@section('title', 'Blog — Single Post')

@section('content')
{{-- Sub-banner — ported verbatim from 10-Single-Post.html:124–135 --}}
<div class="sub-banner">
    <div class="overlay">
        <div class="container">
            <h1>blog</h1>
            <ol class="breadcrumb">
                <li class="pull-left">blog</li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">blog</li>
            </ol>
        </div>
    </div>
</div>

{{-- Single-post detail — ported verbatim from 10-Single-Post.html:137–455.
     Copy is the template's static filler; comments form is markup-only (no
     endpoint) — same posture as the newsletter subscribe elsewhere. --}}
<section class="properti-detsil">
    <div class="container">
        <div class="row">

            {{-- LEFT: article --}}
            <div class="col-sm-9">
                <div class="single-post">
                    <div class="blog-page">
                        <section class="blog no-padding">
                            <ul class="row">
                                <li class="col-sm-12">
                                    <div class="b-inner">
                                        <img class="img-responsive" src="{{ asset('images/b-img-4.jpg') }}" alt="">
                                        <div class="b-details">
                                            <div class="bottom-sec">
                                                <span><i class="fa fa-calendar"></i> mar 23 ,2015</span>
                                                <a class="font-montserrat" href="#.">Just sit right back and you'll hear a tale a tale of a fateful trip that started from this tropic port</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="post-admin">
                                        <img src="{{ asset('images/auther-1.jpg') }}" alt="">
                                        <h6>By Jason mike</h6>
                                        <div class="pull-right margin-t-20">
                                            <span><i class="fa fa-comment-o"></i> 13 Commnets </span> |
                                            <span><i class="fa fa-heart-o"></i> 26 Likes </span> |
                                            <span><i class="fa fa-eye"></i> 30 Viewers </span>
                                        </div>
                                    </div>

                                    <p>So join us here each week my friends you're sure to get a smile from seven stranded castaways here on Gilligans Isle. Texas tea. It's time to put on makeup. It's time to dress up right. It's time to raise the curtain on the Muppet Show tonight. The Brady Bunch the Brady Bunch that's the way we all became the Brady Bunch. Their house is a museum where people come to see ‘em. It's time to raise the curtain on the Muppet Show tonight.</p>
                                    <p>These days are all Happy and Free. These days are all share them with me oh baby. Come and play. Everything's A-OK. Friendly neighbors there that's where we meet. Can you tell me how to get how to get to Sesame Street. On the most sensational inspirational celebrational Muppetational</p>

                                    <blockquote>We finally got a piece of the pie. It's a beautiful day in this neighborhood a beautiful day for neighbor. Would you be mine? Could you be mine? Its a neighborly day in this beautywood a neighborly day for a beauty. Would you be mine Could you be mine.</blockquote>

                                    <ul class="row single-imges">
                                        @foreach(['single-img-1.jpg', 'single-img-2.jpg', 'single-img-3.jpg', 'single-img-4.jpg'] as $img)
                                            <li class="col-sm-3">
                                                <div class="obre">
                                                    <img class="img-responsive" src="{{ asset('images/'.$img) }}" alt="">
                                                    <a href="#"><i class="fa fa-search"></i></a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <p>We're gonna make our dreams come true. The year is 1987 and NASA launches the last of Americas deep space probes. Come and listen to a story about a man named Jed - a poor mountaineer barely kept his family fed. Space. The final frontier. These are the voyages of the Starship Enterprise. And when the odds are against him and their dangers work to do.</p>

                                    <ul class="tags">
                                        <li><a class="font-montserrat text-uppercase" href="#.">home</a></li>
                                        <li><a class="font-montserrat text-uppercase" href="#.">rent</a></li>
                                        <li><a class="font-montserrat text-uppercase" href="#.">property</a></li>
                                    </ul>

                                    {{-- About author --}}
                                    <h4 class="text-uppercase margin-t-40">About author</h4>
                                    <div class="admin-info">
                                        <ul>
                                            <li class="col-xs-3 no-padding text-center">
                                                <img class="img-responsive" src="{{ asset('images/agent-2.jpg') }}" alt="">
                                                <ul class="social_icons">
                                                    <li class="facebook"><a href="#."><i class="fa fa-facebook"></i></a></li>
                                                    <li class="twitter"><a href="#."><i class="fa fa-twitter"></i></a></li>
                                                    <li class="linkedin"><a href="#."><i class="fa fa-linkedin"></i></a></li>
                                                </ul>
                                            </li>
                                            <li class="col-xs-9">
                                                <h3>About Author</h3>
                                                <h5 class="text-uppercase no-margin">Jason Mike</h5>
                                                <br>
                                                <p>In a freak mishap Ranger 3 and its pilot Captain William Buck Rogers are blown out of their trajectory into an orbit which freezes his life support systems and returns Buck Rogers to Earth five-hundred years later. Goodbye gray sky hello blue. There's nothing can hold me when I hold you. Feels so right it cant be wrong. Rockin' and rollin' all week long. We're gonna do it. On your mark get set and go now.</p>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- Comments (markup-only — no backend) --}}
                                    <div class="comments">
                                        <h4 class="text-uppercase">comments (3)</h4>
                                        <ul class="media-list">
                                            <li class="media">
                                                <div class="media-left"><a href="#"><img class="media-object" src="{{ asset('images/avatar-2.jpg') }}" alt=""></a></div>
                                                <div class="media-body">
                                                    <h6 class="media-heading">Cory Anderson<span> 2 April - 2015</span></h6>
                                                    <a href="#." class="btn"> REPLY &nbsp; </a>
                                                    <p>Love exciting and new. Come aboard were expecting you. Love life's sweetest reward Let it flow it floats back to you. Well we're movin' on up to the east side.</p>
                                                </div>
                                            </li>
                                            <li class="media">
                                                <div class="media-left"><a href="#"><img class="media-object" src="{{ asset('images/avatar-3.jpg') }}" alt="..."></a></div>
                                                <div class="media-body">
                                                    <h6 class="media-heading">ROCK LANCER <span> 2 April - 2015</span></h6>
                                                    <a href="#." class="btn"> REPLY &nbsp; </a>
                                                    <p>Love exciting and new. Come aboard were expecting you. Love life's sweetest reward Let it flow it floats back to you. Well we're movin' on up to the east side.</p>
                                                </div>
                                            </li>
                                        </ul>

                                        <h4 class="text-uppercase">leave a comment</h4>
                                        <form>
                                            <ul class="row">
                                                <li class="col-sm-4">
                                                    <input type="text" class="form-control" name="name" placeholder="NAME" required>
                                                </li>
                                                <li class="col-sm-4">
                                                    <input type="email" class="form-control" name="email" placeholder="EMAIL" required>
                                                </li>
                                                <li class="col-sm-4">
                                                    <input type="text" class="form-control" name="subject" placeholder="SUBJECT" required>
                                                </li>
                                                <li class="col-sm-12">
                                                    <label>
                                                        <textarea class="form-control" placeholder="MESSAGE"></textarea>
                                                    </label>
                                                </li>
                                                <li class="col-sm-12">
                                                    <button type="submit" class="btn">Post comment</button>
                                                </li>
                                            </ul>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="col-sm-3 side-bar">
                @include('partials.blog-sidebar')
            </div>
        </div>
    </div>
</section>
@endsection
