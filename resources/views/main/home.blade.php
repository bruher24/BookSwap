@extends('layout')
@section('title')
    Главная
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Рекомендации</h4>
        <div class="row mt-3">
            @include('books.list')
        </div>
    </div>
@endsection
