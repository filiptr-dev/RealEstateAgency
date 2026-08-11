@extends('layouts.panel')

@section('title', 'Users')

@section('content')
    <h1>Users</h1>
    <table class="table" style="width:100%;background:#fff;">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role?->label() }}</td>
                <td><a href="{{ route('panel.admin.users.edit', $user) }}">Edit</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>{{ $users->links() }}</div>
@endsection
