@extends('layout')
@section('title')
    Жанры
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Жанры</h4>
        <div class="row mt-3">
            @foreach($genres as $genre)
                <div class="row w-50">
                    <a class="nav-link"
                       href="{{ route('genres.books', ['genre' => $genre->id]) }}">{{ $genre->name }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
