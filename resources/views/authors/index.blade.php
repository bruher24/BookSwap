@extends('layout')
@section('title')
    Авторы
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Авторы</h4>
        <div class="row mt-3">
            @foreach($authors as $author)
                <div class="row w-50">
                    <a class="nav-link" href="{{ route('authors.books', ['author' => $author->id]) }}">{{ $author->fullName }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
