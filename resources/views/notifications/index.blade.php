@extends('layout')
@section('title')
    Уведомления
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Уведомления</h4>
        <div class="notifications-container"
             data-user="{{ $user->id }}">
            @include('notifications.list')
        </div>
    </div>
@endsection
