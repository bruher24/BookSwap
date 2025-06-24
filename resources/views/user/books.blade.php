@extends('layout')
@section('title')
    Мои книги
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Мои книги</h4>

        <!--FILTERS-->
        <div class="btn-group mt-2" role="group" aria-label="Filters">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Жанр
                </button>
                <ul class="dropdown-menu">
                    @foreach($genres as $genre)
                        <li><a class="dropdown-item" href="#">{{ $genre->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Автор
                </button>
                <ul class="dropdown-menu">
                    @foreach($authors as $author)
                        <li><a class="dropdown-item" href="#">{{ $author->formattedName }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Год издания
                </button>
                <ul class="dropdown-menu">
                    @foreach($years as $year)
                        <li><a class="dropdown-item" href="#">{{ $year }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Тип
                </button>
                <ul class="dropdown-menu">
                    @foreach($types as $type)
                        <li><a class="dropdown-item" href="#">{{ $type->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row mt-3">
            @foreach($books as $book)
                <div class="col-auto rounded-1 border shadow mb-5 me-2 p-0 d-flex flex-column" style="width: 200px; height: 320px; cursor: pointer;">
                    <img alt="book_image" src="https://via.placeholder.com/200x250" class="img-fluid rounded-top" style="width: 100%; height: 250px; object-fit: cover;">
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
