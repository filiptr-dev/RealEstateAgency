@extends('layouts.panel')

@section('title', 'Properties')

@section('content')
    <h4 class="panel-section-title">
        Properties
        <span class="panel-title-actions">
            <a href="{{ route('panel.properties.create') }}" class="panel-btn">+ New Property</a>
        </span>
    </h4>

    <div class="panel-card panel-card-flush">
        <table class="panel-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>City</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($properties as $property)
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
                    <td>{{ $property->status?->label() }}</td>
                    <td>{{ $property->priceFormatted }}</td>
                    <td>{{ $property->published_at ? 'Yes' : 'Draft' }}</td>
                    <td>
                        @can('update', $property)
                            <a class="panel-action-edit" href="{{ route('panel.properties.edit', $property) }}">Edit</a>
                        @endcan
                        @can('delete', $property)
                            <form method="POST" action="{{ route('panel.properties.destroy', $property) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="panel-action-delete" onclick="return confirm('Delete this property?')">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No properties yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $properties->links() }}</div>
@endsection
