@extends('layouts.panel')

@section('title', 'Inquiries')

@section('content')
    <h4 class="panel-section-title">Inquiries</h4>

    <div class="panel-card panel-card-flush">
        <table class="panel-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>From</th>
                    <th>Property</th>
                    <th>Subject</th>
                    <th>Received</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($submissions as $submission)
                <tr>
                    <td>
                        @if($submission->read_at)
                            <span class="panel-badge panel-badge-read">Read</span>
                        @else
                            <span class="panel-badge panel-badge-unread">Unread</span>
                        @endif
                    </td>
                    <td>
                        <span class="panel-cell-title">{{ $submission->name }}</span><br>
                        <small style="color:#999;">{{ $submission->email }}</small>
                    </td>
                    <td>{{ $submission->property?->title ?? '—' }}</td>
                    <td>{{ $submission->subject ?? '(no subject)' }}</td>
                    <td>{{ $submission->created_at->diffForHumans() }}</td>
                    <td>
                        <a class="panel-action-view" href="{{ route('panel.inquiries.show', $submission) }}">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No inquiries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $submissions->links() }}</div>
@endsection
