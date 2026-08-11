@extends('layouts.app')

@section('title', 'Contact us')

@section('content')
<section class="inner-banner" style="background:#333;padding:80px 0;color:#fff;text-align:center;">
    <div class="container"><h1>Contact Us</h1></div>
</section>

<section style="padding:60px 0;">
    <div class="container">
        @include('partials.flash')

        <div class="row">
            <div class="col-sm-8">
                <h3>Send us a message</h3>
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn">Send</button>
                </form>
            </div>
            <div class="col-sm-4">
                <h3>Reach us</h3>
                <p><i class="fa fa-map-marker"></i> 09 Design Street, Downtown, Sydney</p>
                <p><i class="fa fa-phone"></i> +01 123 456 78</p>
                <p><i class="fa fa-envelope-o"></i> info@realtor.example</p>
                <p><i class="fa fa-clock-o"></i> Mon–Fri, 9:00–18:00</p>
            </div>
        </div>
    </div>
</section>
@endsection
