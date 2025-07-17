@extends('layout')
@section('title')
    {{ $book->name }}
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">{{ $book->name }}</h4>
        <div class="">
            <img src="{{ $book->cover->src }}" alt="">
        </div>
    </div>
@endsection