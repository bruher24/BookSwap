@extends('layout')
@section('title')
    Результаты поиска
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Нашлось:</h4>
        <div class="row mt-3">
            @foreach($found as $section)
                @foreach($section as $item)
                    @if (isset($item->name))
                        <p>Book: {{ $item->name }}</p>
                    @elseif(isset($item->fullName))
                        <p>Author: {{ $item->fullName }}</p>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
@endsection
