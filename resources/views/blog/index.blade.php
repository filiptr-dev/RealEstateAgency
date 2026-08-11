@extends('layouts.app')

@section('title', 'Blog')

@section('content')
{{-- Sub-banner — ported verbatim from 09-Blog.html:124–135 --}}
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

{{-- Blog listing + sidebar — ported verbatim from 09-Blog.html:137–390.
     Content is static/filler (matches the template) — v1 has no Post model.
     Every "read more" style anchor points at route('blog.show') since there
     is one fixed demo post; a real slug table is a later batch. --}}
<section class="properti-detsil">
    <div class="container">
        <div class="row">

            {{-- LEFT BAR --}}
            <div class="col-sm-9">
                <div class="blog-page">
                    <section class="blog no-padding">
                        <ul class="row">
                            @foreach(['b-img-1.jpg', 'b-img-2.jpg', 'b-img-3.jpg', 'b-img-4.jpg', 'b-img-5.jpg'] as $img)
                                <li class="col-sm-12">
                                    <div class="b-inner">
                                        <img class="img-responsive" src="{{ asset('images/'.$img) }}" alt="">
                                        <div class="b-details">
                                            <div class="bottom-sec">
                                                <span><i class="fa fa-calendar"></i> mar 23 ,2015</span>
                                                <a class="font-montserrat" href="{{ route('blog.show') }}">Just sit right back and you'll hear a tale a tale of a fateful trip that started from this tropic port</a>
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
                                    <p>They'll have to make the best of things its an uphill climb. Baby if you've ever wondered - wondered whatever became of me. I'm living on the air in Cincinnati. Cincinnati WKRP. The Love Boat soon will be making another run. The Love Boat promises something for everyone.</p>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                </div>
            </div>

            {{-- RIGHT SIDEBAR — 09-Blog.html:226–387 --}}
            <div class="col-sm-3 side-bar">
                @include('partials.blog-sidebar')
            </div>
        </div>
    </div>
</section>
@endsection
