@extends('layouts.panel')

@section('title', 'Inquiries')

@section('content')
    <h1>Inquiries</h1>
    <table class="table" style="width:100%;background:#fff;">
        <thead><tr><th>From</th><th>Property</th><th>Subject</th><th>Received</th><th></th></tr></thead>
        <tbody>
        @forelse($submissions as $submission)
            <tr style="{{ $submission->read_at ? '' : 'font-weight:bold;' }}">
                <td>{{ $submission->name }}<br><small>{{ $submission->email }}</small></td>
                <td>{{ $submission->property?->title ?? '—' }}</td>
                <td>{{ $submission->subject ?? '(no subject)' }}</td>
                <td>{{ $submission->created_at->diffForHumans() }} {!! $submission->read_at ? '' : '<span style="color:#c00;">(new)</span>' !!}</td>
                <td><a href="{{ route('panel.inquiries.show', $submission) }}">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="5">No inquiries yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div>{{ $submissions->links() }}</div>
@endsection
