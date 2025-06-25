@extends('layout')
@section('title')
    Мои книги
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Мои книги</h4>

        @include('utils.filters')

        @include('books.list')
    </div>
@endsection
