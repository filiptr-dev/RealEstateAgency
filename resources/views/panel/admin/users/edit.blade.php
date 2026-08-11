@extends('layouts.panel')

@section('title', 'Edit User')

@section('content')
    <a href="{{ route('panel.admin.users.index') }}">← Users</a>
    <h1>Edit {{ $user->name }}</h1>
    <form method="POST" action="{{ route('panel.admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control">
                @foreach(\App\Enums\UserRole::cases() as $r)
                    <option value="{{ $r->value }}" @selected($user->role?->value===$r->value)>{{ $r->label() }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
