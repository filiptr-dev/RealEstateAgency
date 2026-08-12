{{-- Blog sidebar — categories + recent posts + tags from DB --}}

{{-- CATEGORIES --}}
<div class="categories margin-t-40">
    <h5>Categories</h5>
    <hr>
    <ul>
        @forelse($categories as $cat)
            <li><a href="#.">{{ $cat }}</a></li>
        @empty
            <li><span>No categories yet.</span></li>
        @endforelse
    </ul>
</div>

{{-- RECENT POSTS --}}
<div class="recent-come margin-t-40">
    <h5>Recent Posts</h5>
    <hr>
    <ul class="recent-come">
        @forelse($recentPosts as $rp)
            <li>
                @if($rp->featured_image)
                    <div class="img-post"><img src="{{ $rp->featured_image }}" alt="" style="height:64px;width:80px;object-fit:cover;"></div>
                @endif
                <div class="text-post">
                    <a href="{{ route('blog.show', $rp) }}">{{ \Illuminate\Support\Str::limit($rp->title, 50) }}</a>
                    <span>{{ $rp->published_at->format('M d, Y') }}</span>
                </div>
            </li>
        @empty
            <li><span>No recent posts.</span></li>
        @endforelse
    </ul>
</div>

{{-- SOCIAL --}}
<div class="socil-action margin-t-40">
    <h5>Social With us</h5>
    <hr>
    <ul>
        <li><a class="rss" href="#."><i class="fa fa-rss"></i>RSS FEED</a></li>
        <li><a class="tw" href="#."><i class="fa fa-twitter"></i>follow us</a></li>
        <li><a class="fb" href="#."><i class="fa fa-facebook"></i>LIKE US</a></li>
        <li><a class="pin" href="#."><i class="fa fa-pinterest"></i>follow us</a></li>
    </ul>
</div>

{{-- TAGS --}}
<div class="tags margin-t-40">
    <h5>Tags</h5>
    <hr>
    <ul>
        <li><a href="#.">Buying</a></li>
        <li><a href="#.">Selling</a></li>
        <li><a href="#.">Investing</a></li>
        <li><a href="#.">Market</a></li>
        <li><a href="#.">Tips</a></li>
        <li><a href="#.">Renovation</a></li>
    </ul>
</div>
