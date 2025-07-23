@extends('layout')
@section('title')
    Сообщения
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Сообщения</h4>
        @include('chat.list')
    </div>
@endsection
