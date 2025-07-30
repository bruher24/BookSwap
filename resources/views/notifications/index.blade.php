@extends('layout')
@section('title')
    Уведомления
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Уведомления</h4>
        
        @include('notifications.list')
    </div>
@endsection
