@extends('layout')
@section('title')
    Главная
@endsection
@section('main')
    <div class="container">
        <h4 class="mt-4">Рекомендации</h4>
        <div class="row mt-3">
            @foreach($books as $book)
                <div class="col-auto rounded-1 border shadow mb-5 me-2 p-0 d-flex flex-column" style="width: 200px; height: 320px; cursor: pointer;">
                    <img src="https://via.placeholder.com/200x250" class="img-fluid rounded-top" style="width: 100%; height: 250px; object-fit: cover;">
                    <div class="p-2 flex-grow-1 d-flex flex-column">
                        <h6 class="mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 2.5em; line-height: 1.25em;" title="Очень длинное название товара, которое не помещается в две строки и будет обрезано с многоточием">
                            Очень длинное название товара, которое не помещается в две строки и будет обрезано с многоточием
                        </h6>
                        <p class="mb-0 fw-bold mt-auto">999 ₽</p>
                    </div>
                </div>
            @endforeach

            <!-- Карточка товара 2 -->
            <div class="col-auto rounded-1 border shadow mb-5 me-2 p-0 d-flex flex-column" style="width: 200px; height: 320px; cursor: pointer;">
                <img src="https://via.placeholder.com/200x250" class="img-fluid rounded-top" style="width: 100%; height: 250px; object-fit: cover;">
                <div class="p-2 flex-grow-1 d-flex flex-column">
                    <h6 class="mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 2.5em; line-height: 1.25em;" title="Название в две строки">
                        Название в две строки<br>Вторая строка
                    </h6>
                    <p class="mb-0 fw-bold mt-auto">799 ₽</p>
                </div>
            </div>

            <!-- Карточка товара 3 -->
            <div class="col-auto rounded-1 border shadow mb-5 me-2 p-0 d-flex flex-column" style="width: 200px; height: 320px; cursor: pointer;">
                <img src="https://via.placeholder.com/200x250" class="img-fluid rounded-top" style="width: 100%; height: 250px; object-fit: cover;">
                <div class="p-2 flex-grow-1 d-flex flex-column">
                    <h6 class="mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 2.5em; line-height: 1.25em;" title="Короткое название">
                        Короткое название
                    </h6>
                    <p class="mb-0 fw-bold mt-auto">1 299 ₽</p>
                </div>
            </div>
        </div>
    </div>
@endsection