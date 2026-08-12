@extends('layouts.panel')

@section('title', 'Blog Posts')

@section('content')
    <h4 class="panel-section-title">Blog Posts</h4>

    <div class="panel-card panel-card-flush">
        <table class="panel-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>
                        <a class="panel-cell-title" href="{{ route('blog.show', $post) }}" target="_blank" rel="noopener">
                            {{ $post->title }}
                        </a>
                    </td>
                    <td>{{ $post->author?->name ?? '—' }}</td>
                    <td>{{ $post->category ?? '—' }}</td>
                    <td>
                        @if($post->published_at)
                            {{ $post->published_at->format('M j, Y') }}
                        @else
                            <span style="color:#999;">Draft</span>
                        @endif
                    </td>
                    <td>
                        {{-- CRUD deferred to a future batch — link is a placeholder. --}}
                        <a class="panel-action-edit" href="#">Edit</a>
                        <button type="button" class="panel-action-delete" disabled title="Delete not available yet">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No posts yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $posts->links() }}</div>
@endsection
