@extends('layouts.panel')

@section('title', 'Edit Property')

@section('content')
    <h1>Edit property</h1>
    <form method="POST" action="{{ route('panel.properties.update', $property) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('panel.properties._form', ['property' => $property])
    </form>
@endsection
