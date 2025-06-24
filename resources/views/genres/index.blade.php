@extends('layout')
@section('title')
    Жанры
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Жанры</h4>
        <div class="row mt-3">
            @foreach($genres as $genre)
                <div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
