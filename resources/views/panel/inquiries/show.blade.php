@extends('layouts.panel')

@section('title', 'Inquiry')

@section('content')
    <a href="{{ route('panel.inquiries.index') }}">← All inquiries</a>
    <h1>{{ $submission->subject ?? 'Inquiry' }}</h1>
    <p><strong>From:</strong> {{ $submission->name }} &lt;{{ $submission->email }}&gt;
       @if($submission->phone) &middot; {{ $submission->phone }} @endif
    </p>
    @if($submission->property)
        <p><strong>Property:</strong>
           <a href="{{ route('properties.show', $submission->property) }}">{{ $submission->property->title }}</a>
        </p>
    @endif
    <p><strong>Received:</strong> {{ $submission->created_at }}</p>
    <hr>
    <div style="white-space:pre-line;">{{ $submission->message }}</div>
@endsection
