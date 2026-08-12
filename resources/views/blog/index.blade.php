@extends('layouts.app')

@section('title', 'Blog')

@section('content')
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

<section class="properti-detsil">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <div class="blog-page">
                    <section class="blog no-padding">
                        <ul class="row">
                            @forelse($posts as $post)
                                <li class="col-sm-12">
                                    <div class="b-inner">
                                        @if($post->featured_image)
                                            <img class="img-responsive" src="{{ $post->featured_image }}" alt="{{ $post->title }}" style="height:260px;width:100%;object-fit:cover;">
                                        @endif
                                        <div class="b-details">
                                            <div class="bottom-sec">
                                                <span><i class="fa fa-calendar"></i> {{ $post->published_at->format('M d, Y') }}</span>
                                                <a class="font-montserrat" href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="post-admin">
                                        <img src="{{ $post->author?->photoUrl() ?? asset('images/agent-1.jpg') }}" alt="{{ $post->author?->name ?? 'Staff' }}">
                                        <h6>By {{ $post->author?->name ?? 'Staff' }}</h6>
                                        <div class="pull-right margin-t-20">
                                            <span><i class="fa fa-comment-o"></i> {{ $post->comments->count() }} Comments</span> |
                                            <span class="label label-default" style="margin-left:4px;">{{ $post->category }}</span>
                                        </div>
                                    </div>
                                    @if($post->excerpt)
                                        <p>{{ $post->excerpt }}</p>
                                    @endif
                                </li>
                            @empty
                                <li class="col-sm-12"><p>No posts yet.</p></li>
                            @endforelse
                        </ul>
                        <div style="margin-top:20px;">{{ $posts->links() }}</div>
                    </section>
                </div>
            </div>
            <div class="col-sm-3 side-bar">
                @include('partials.blog-sidebar')
            </div>
        </div>
    </div>
</section>
@endsection
