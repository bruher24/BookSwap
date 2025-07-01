@extends('layout')
@section('title')
    {{ $author->fullName }}
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">{{ $author->fullName }}</h4>

        @include('utils.filters')
        @include('books.list')
    </div>
@endsection
