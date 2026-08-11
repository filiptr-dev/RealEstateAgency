@extends('layouts.panel')

@section('title', 'Properties')

@section('content')
    <h1>Properties
        <a href="{{ route('panel.properties.create') }}" class="btn btn-primary" style="float:right;">+ New</a>
    </h1>

    <table class="table" style="width:100%;background:#fff;">
        <thead><tr><th>Title</th><th>City</th><th>Type</th><th>Status</th><th>Price</th><th>Published</th><th></th></tr></thead>
        <tbody>
        @forelse($properties as $property)
            <tr>
                <td>{{ $property->title }}</td>
                <td>{{ $property->city }}</td>
                <td>{{ $property->type?->label() }}</td>
                <td>{{ $property->status?->label() }}</td>
                <td>{{ $property->priceFormatted }}</td>
                <td>{{ $property->published_at ? 'Yes' : 'Draft' }}</td>
                <td>
                    @can('update', $property)
                        <a href="{{ route('panel.properties.edit', $property) }}">Edit</a>
                    @endcan
                    @can('delete', $property)
                        <form method="POST" action="{{ route('panel.properties.destroy', $property) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this property?')" style="background:none;border:none;color:#c00;cursor:pointer;">Delete</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No properties yet.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div>{{ $properties->links() }}</div>
@endsection
