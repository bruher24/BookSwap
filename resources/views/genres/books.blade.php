@extends('layout')
@section('title')
    {{ $genre->name }}
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">{{ $genre->name }}</h4>

        @include('utils.filters')
        @include('books.list')
    </div>
@endsection
