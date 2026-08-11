{{-- Blog sidebar — ported verbatim from 09-Blog.html:226–387 and 10-Single-Post.html:291–452.
     Sidebar links are template filler (`href="#."`) — kept as-is per the "port
     1:1, do not invent" rule. If/when a real blog ships, this partial is the
     one place to swap the finder for real categories/tags/recent posts. --}}

{{-- FIND PROPERTY --}}
<div class="finder">
    <div class="find-sec">
        <h5>Search for properties</h5>
        <ul class="row">
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">City</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">Location</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">Property Status</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">Property Type</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">No of Bedrooms</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">No of Bathrooms</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">Minimum price</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12">
                <select class="selectpicker">
                    <option value="">Maximum price</option>
                    <option value="">Cat 1</option>
                    <option value="">Cat 2</option>
                </select>
            </li>
            <li class="col-sm-12"><a href="#." class="btn">SEARCH</a></li>
        </ul>
    </div>
</div>

{{-- CATEGORIES --}}
<div class="categories margin-t-40">
    <h5>Categories</h5>
    <hr>
    <ul>
        <li><a href="#."> Architecture <span>(23)</span></a></li>
        <li><a href="#."> Art Home <span>(16)</span></a></li>
        <li><a href="#."> Beautyful Villa <span> (09)</span></a></li>
        <li><a href="#."> Fantastic House <span>(15)</span></a></li>
        <li><a href="#."> Home With Pool <span> (11)</span></a></li>
        <li><a href="#."> Beauty Office <span>(06)</span></a></li>
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
        <li><a class="drib" href="#."><i class="fa fa-dribbble"></i>follow us</a></li>
        <li><a class="g-plus" href="#."><i class="fa fa-google-plus"></i>plus 1 us</a></li>
    </ul>
</div>

{{-- FEATURED PROPERTIES --}}
<div class="flicker-post margin-t-40">
    <h5>featured properties</h5>
    <hr>
    <ul>
        @foreach(['img-1.jpg', 'img-2.jpg', 'img-3.jpg', 'img-4.jpg', 'img-5.jpg', 'img-6.jpg'] as $img)
            <li><a href="#."><img class="img-responsive" src="{{ asset('images/'.$img) }}" alt=""></a></li>
        @endforeach
    </ul>
</div>

{{-- RECENT PROPERTIES --}}
<div class="recent-come margin-t-40">
    <h5>Categories</h5>
    <hr>
    <ul class="recent-come">
        @foreach(['recent-img.jpg', 'recent-img-1.jpg', 'recent-img-2.jpg'] as $img)
            <li>
                <div class="img-post"><img src="{{ asset('images/'.$img) }}" alt=""></div>
                <div class="text-post"><a href="#.">On your mark get set and go now</a> <span>$956,205</span></div>
            </li>
        @endforeach
    </ul>
</div>

{{-- TAGS --}}
<div class="tags margin-t-40">
    <h5>TAGS</h5>
    <hr>
    <ul>
        <li><a href="#.">Amazing</a></li>
        <li><a href="#.">Envato</a></li>
        <li><a href="#.">Themes</a></li>
        <li><a href="#.">Clean</a></li>
        <li><a href="#.">Responsiveness</a></li>
        <li><a href="#.">SEO</a></li>
        <li><a href="#.">Mobile</a></li>
        <li><a href="#.">IOS</a></li>
        <li><a href="#.">Flat</a></li>
        <li><a href="#.">Design</a></li>
    </ul>
</div>
