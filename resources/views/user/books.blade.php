@extends('layout')
@section('title')
    Мои книги
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Мои книги</h4>

        @include('utils.filters')

        <div class="d-inline text-end">
            <button id="addBookBtn" type="button" class="bookBtn mt-2 btn btn-outline-dark float-end"
                    data-bs-toggle="modal"
                    data-bs-target="#modalBookForm">Добавить книгу
            </button>
        </div>

        @include('books.list')
    </div>
@endsection
