@extends('profile.layout')
@section('profile.main')
    <!-- Personal Information -->
    <div class="mb-4">
        <h5 class="mb-4">Личные данные</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Имя</label>
                <input type="text" class="form-control" value="{{$user->name}}">
            </div>
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="{{$user->email}}">
            </div>
        </div>
        <h5 class="mt-4">Статистика книг</h5>
        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <p>Отдано: 65</p>
                <p>Взято: 73</p>
            </div>
        </div>
    </div>

    <!-- Contact info -->
{{--    <div class="mb-4">--}}
{{--        <h5 class="mb-4">Контактная информация</h5>--}}
{{--        <div class="row g-3">--}}
{{--            <div class="col-md-6">--}}
{{--                <label class="form-label">Номер телефона</label>--}}
{{--                <div class="row">--}}
{{--                    <div class="col-2 pe-0" style="width: 14%">--}}
{{--                        <select class="form-select w-auto">--}}
{{--                            <option selected>+7</option>--}}
{{--                            <option>+123</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <div class="col-4">--}}
{{--                        <input type="tel" class="form-control" id="ec-mobile-number"--}}
{{--                               aria-describedby="emailHelp" placeholder="+79998887766"--}}
{{--                               value="{{$user->phones()->first()->number ?? ''}}"/>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-3">--}}
{{--                        <input type="button" class="form-control btn btn-success"--}}
{{--                               id="ec-mobile-number" aria-describedby="emailHelp"--}}
{{--                               value="Сохранить"/>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div id="messengers">--}}
{{--            @foreach($user->messengers()->get() as $messenger)--}}
{{--                <div class="row g-3 mt-1">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <label class="form-label">{{ $messenger->name }}</label>--}}
{{--                        <div class="input-group ">--}}
{{--                            <span class="input-group-text">{{ config('constant.telegram_url') }}</span>--}}
{{--                            <input type="text" class="form-control" placeholder="Укажите имя пользователя...">--}}
{{--                            TODO: проверка ссылки по кнопке--}}
{{--                            <button class="btn btn-success">Проверить</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--                <div class="dropdown my-2">--}}
{{--                    <a class="d-block link-light text-decoration-none dropdown-toggle w-25" id="dropdownUser1"--}}
{{--                       data-bs-toggle="dropdown" aria-expanded="false"--}}
{{--                       style="cursor: pointer">--}}
{{--                        <button class="btn btn-success">Добавить мессенджер</button>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">--}}
{{--                        @foreach(config('constant.messengers') as $type)--}}
{{--                            <li>--}}
{{--                                TODO: сделать красиво--}}
{{--                                <a class="dropdown-item messenger-select">--}}
{{--                                    TODO: добавить значок мессенджера--}}
{{--                                    {{ $type }}--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
