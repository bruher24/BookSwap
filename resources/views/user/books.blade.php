@extends('layout')
@section('title')
    Мои книги
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Мои книги</h4>

        @include('utils.filters')

        <div class="row mt-3">
            @foreach($books as $book)
                <div class="col-auto rounded-1 border shadow mb-5 me-2 p-0 d-flex flex-column"
                     style="width: 200px; height: 320px; cursor: pointer;">
                    <img alt="book_image" src="https://via.placeholder.com/200x250" class="img-fluid rounded-top"
                         style="width: 100%; height: 250px; object-fit: cover;">
                    <div class="p-2 flex-grow-1 d-flex flex-column">
                        <h6 class="mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
                        overflow: hidden; text-overflow: ellipsis; min-height: 2.5em; line-height: 1.25em;">
                            {{ $book->mainAuthor }} {{ $book->name }}
                        </h6>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
