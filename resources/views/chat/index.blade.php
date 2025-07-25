@extends('layout')
@section('title')
    Сообщения
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Сообщения</h4>
        <div class="chat-container row mt-3 border border-black w-100 p-0 m-0"
             data-user="{{ $user->id }}"
             style="height: 83vh">
            @include('chat.list')
            @include('chat.chatbox')
        </div>
    </div>
@endsection
