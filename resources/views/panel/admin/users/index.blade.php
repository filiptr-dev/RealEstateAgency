@extends('layouts.panel')

@section('title', 'Users')

@section('content')
    <h4 class="panel-section-title">Agents & Users</h4>

    <div class="panel-card panel-card-flush">
        <table class="panel-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td><span class="panel-cell-title">{{ $user->name }}</span></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                            $roleValue = $user->role?->value ?? 'user';
                            $badgeClass = match($roleValue) {
                                'admin' => 'panel-badge-role-admin',
                                'agent' => 'panel-badge-role-agent',
                                default => 'panel-badge-role-user',
                            };
                        @endphp
                        <span class="panel-badge {{ $badgeClass }}">{{ $user->role?->label() }}</span>
                    </td>
                    <td>
                        <a class="panel-action-edit" href="{{ route('panel.admin.users.edit', $user) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $users->links() }}</div>
@endsection
