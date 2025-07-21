@extends('layout')
@section('title')
    {{ $book->name }}
@endsection
@section('main')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-4 border mb-5 me-2 p-0 d-flex flex-column">
                <img alt="Упс! Произошла ошибка." src="{{ $book->cover->src }}"
                     class="img-fluid"
                     style="width: 100%; height: 600px; object-fit: cover; text-align: center; line-height: 600px; color: grey;">
            </div>

            <div class="col-md-4 ms-4">
                <h4 class="mt-5">{{ $book->name }}</h4>
                <h4 class="mt-4">{{ $book->mainAuthor }}</h4>
                <h4 class="mt-4">{{ $book->book_type->label() }}</h4>
                <h4 class="mt-4">{{ $book->user()->first()->name }}</h4>

                <div class="btn-toolbar mt-3" role="toolbar">
                    <div class="btn-group">
                        <a class="btn btn-outline-dark" id="chat_btn"
                           href="{{ route('users.chat', ['user' => $user, 'chatWith' => $book->user_id]) }}"
                           data-user="{{ $user->id ?? null }}"
                           data-book="{{ $book->id }}">
                            Написать
                        </a>
                        <button class="btn btn-outline-dark" id="call_btn"
                                data-user="{{ $user->id ?? null }}"
                                data-seller="{{ $sellerPhone }}">
                            Позвонить
                        </button>
                    </div>
                    <div class="ms-1" role="group">
                        <form id="add_to_favorites_form"
                              action="{{ route('users.addToFavorites', ['user' => $user]) }}"
                              method="post">
                            @method('put')
                            @csrf
                            <input id="favorites_user_id" type="hidden" name="user_id" value="{{ $user->id ?? null }}">
                            <input id="favorites_book_id" type="hidden" name="book_id" value="{{ $book->id }}">
                            <input id="favorites_state" type="hidden" name="state" value="{{ $isBookLiked }}">
                        </form>
                        <button class="btn p-1 dots-icon like-btn
                        @if($isBookLiked)
                            liked
                        @endif
                        " id="like_btn">
                            <svg height="30px" width="30px" class="heart-icon" viewBox="0 0 122.88 109.57">
                                <path fill="none"
                                      d="M65.46,19.57c-0.68,0.72-1.36,1.45-2.2,2.32l-2.31,2.41l-2.4-2.33c-0.71-0.69-1.43-1.4-2.13-2.09 c-7.42-7.3-13.01-12.8-24.52-12.95c-0.45-0.01-0.93,0-1.43,0.02c-6.44,0.23-12.38,2.6-16.72,6.65c-4.28,4-7.01,9.67-7.1,16.57 c-0.01,0.43,0,0.88,0.02,1.37c0.69,19.27,19.13,36.08,34.42,50.01c2.95,2.69,5.78,5.27,8.49,7.88l11.26,10.85l14.15-14.04 c2.28-2.26,4.86-4.73,7.62-7.37c4.69-4.5,9.91-9.49,14.77-14.52c3.49-3.61,6.8-7.24,9.61-10.73c2.76-3.42,5.02-6.67,6.47-9.57 c2.38-4.76,3.13-9.52,2.62-13.97c-0.5-4.39-2.23-8.49-4.82-11.99c-2.63-3.55-6.13-6.49-10.14-8.5C96.5,7.29,91.21,6.2,85.8,6.82 C76.47,7.9,71.5,13.17,65.46,19.57L65.46,19.57z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection