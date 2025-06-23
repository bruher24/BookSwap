@extends('layout')
@section('title')
    Главная
@endsection
@section('main')
    @guest
{{--        @include('home.guestHome')--}}
    @endguest
    @auth
{{--        @include('home.authHome')--}}
    @endauth
@endsection
