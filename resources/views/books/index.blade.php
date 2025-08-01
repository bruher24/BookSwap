@extends('layout')
@section('title')
    Каталог
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Список книг</h4>

        @include('utils.filters')

        @include('books.list')
    </div>
@endsection
