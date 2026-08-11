@extends('layouts.panel')

@section('title', 'New Property')

@section('content')
    <h1>New property</h1>
    <form method="POST" action="{{ route('panel.properties.store') }}" enctype="multipart/form-data">
        @include('panel.properties._form', ['property' => $property])
    </form>
@endsection
