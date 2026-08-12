@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
    <h4 class="panel-section-title">Dashboard</h4>

    <div class="row">
        <div class="col-sm-3">
            <div class="panel-stat-card">
                <i class="fa fa-home panel-stat-icon"></i>
                <div class="panel-stat-number">{{ $counts['properties'] }}</div>
                <div class="panel-stat-label">Total Properties</div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel-stat-card">
                <i class="fa fa-envelope-o panel-stat-icon"></i>
                <div class="panel-stat-number">{{ $counts['open_inquiries'] }}</div>
                <div class="panel-stat-label">Open Inquiries</div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel-stat-card">
                <i class="fa fa-pencil-square-o panel-stat-icon"></i>
                <div class="panel-stat-number">{{ $counts['published_posts'] }}</div>
                <div class="panel-stat-label">Published Posts</div>
            </div>
        </div>
        @if(auth()->user()->isAdmin())
            <div class="col-sm-3">
                <div class="panel-stat-card">
                    <i class="fa fa-users panel-stat-icon"></i>
                    <div class="panel-stat-number">{{ $counts['users'] ?? 0 }}</div>
                    <div class="panel-stat-label">Registered Users</div>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-8">
            <div class="panel-card panel-card-flush">
                <div class="panel-card-head"><h5>Recent Properties</h5></div>
                <div class="panel-card-body">
                    <table class="panel-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>City</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Published</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentProperties as $property)
                            <tr>
                                <td>
                                    @can('update', $property)
                                        <a class="panel-cell-title" href="{{ route('panel.properties.edit', $property) }}">{{ $property->title }}</a>
                                    @else
                                        <span class="panel-cell-title">{{ $property->title }}</span>
                                    @endcan
                                </td>
                                <td>{{ $property->city }}</td>
                                <td>{{ $property->type?->label() }}</td>
                                <td>{{ $property->priceFormatted }}</td>
                                <td>{{ $property->published_at ? $property->published_at->format('M j, Y') : 'Draft' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No properties yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    <a href="{{ route('panel.properties.index') }}" class="panel-view-all">View all →</a>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="panel-card panel-card-flush">
                <div class="panel-card-head"><h5>Recent Inquiries</h5></div>
                <div class="panel-card-body">
                    @forelse($recentInquiries as $inquiry)
                        <div class="panel-inquiry-item">
                            <div class="panel-inquiry-name">{{ $inquiry->name }}</div>
                            <div class="panel-inquiry-body">{{ \Illuminate\Support\Str::limit($inquiry->message, 50) }}</div>
                            <div class="panel-inquiry-date">{{ $inquiry->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p style="color:#999;font-size:13px;">No inquiries yet.</p>
                    @endforelse
                    <a href="{{ route('panel.inquiries.index') }}" class="panel-view-all">View all →</a>
                </div>
            </div>
        </div>
    </div>
@endsection
