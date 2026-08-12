@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="sub-banner">
    <div class="overlay">
        <div class="container">
            <h1>blog</h1>
            <ol class="breadcrumb">
                <li class="pull-left">blog</li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('blog.index') }}">blog</a></li>
                <li class="active">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</li>
            </ol>
        </div>
    </div>
</div>

<section class="properti-detsil">
    <div class="container">
        @include('partials.flash')
        <div class="row">
            <div class="col-sm-9">
                <div class="single-post">
                    <div class="blog-page">
                        <section class="blog no-padding">
                            <ul class="row">
                                <li class="col-sm-12">
                                    @if($post->featured_image)
                                        <div class="b-inner">
                                            <img class="img-responsive" src="{{ $post->featured_image }}" alt="{{ $post->title }}" style="width:100%;height:auto;object-fit:cover;">
                                            <div class="b-details">
                                                <div class="bottom-sec">
                                                    <span><i class="fa fa-calendar"></i> {{ $post->published_at->format('M d, Y') }}</span>
                                                    <a class="font-montserrat" href="#.">{{ $post->title }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <h2>{{ $post->title }}</h2>
                                    @endif

                                    <div class="post-admin">
                                        <img src="{{ asset('images/auther-1.jpg') }}" alt="">
                                        <h6>By {{ $post->author?->name ?? 'Staff' }}</h6>
                                        <div class="pull-right margin-t-20">
                                            <span><i class="fa fa-comment-o"></i> {{ $post->comments->count() }} Comments </span> |
                                            <span class="label label-default" style="margin-left:4px;">{{ $post->category }}</span>
                                        </div>
                                    </div>

                                    <div class="post-body" style="margin-top:20px;">
                                        {!! nl2br(e($post->body)) !!}
                                    </div>

                                    {{-- Comments --}}
                                    <div class="comments margin-t-40">
                                        <h4 class="text-uppercase">comments ({{ $post->comments->count() }})</h4>
                                        @if($post->comments->isEmpty())
                                            <p><em>No comments yet — be the first.</em></p>
                                        @else
                                            <ul class="media-list">
                                                @foreach($post->comments as $comment)
                                                    <li class="media">
                                                        <div class="media-left"><img class="media-object" src="{{ asset('images/avatar-2.jpg') }}" alt=""></div>
                                                        <div class="media-body">
                                                            <h6 class="media-heading">{{ $comment->user?->name ?? 'Anonymous' }}<span> {{ $comment->created_at->format('M d, Y') }}</span></h6>
                                                            <p>{{ $comment->body }}</p>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        <h4 class="text-uppercase margin-t-40">leave a comment</h4>
                                        @auth
                                            <form method="POST" action="{{ route('blog.comments.store', $post) }}">
                                                @csrf
                                                <ul class="row">
                                                    <li class="col-sm-12">
                                                        <textarea name="body" class="form-control" rows="4" placeholder="Your comment" required minlength="2" maxlength="2000">{{ old('body') }}</textarea>
                                                        @error('body') <span style="color:#c00;">{{ $message }}</span> @enderror
                                                    </li>
                                                    <li class="col-sm-12" style="margin-top:10px;">
                                                        <button type="submit" class="btn">Post comment</button>
                                                    </li>
                                                </ul>
                                            </form>
                                        @else
                                            <p><a href="{{ route('login') }}" class="btn">Log in to comment</a></p>
                                        @endauth
                                    </div>
                                </li>
                            </ul>
                        </section>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 side-bar">
                @include('partials.blog-sidebar')
            </div>
        </div>
    </div>
</section>
@endsection
