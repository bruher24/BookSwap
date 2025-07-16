@extends('profile.layout')
@section('profile.main')

    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
        @method('PATCH')
        @csrf
        <!-- Personal Information -->
        <div class="mb-4">
            <h5 class="mb-4">Личные данные</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Имя</label>
                    <input type="text" class="form-control" name="name" value="{{$user->name}}">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{$user->email}}">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-sm-3">
                    <button type="button" id="changePasswordBtn" class="btn btn-secondary">Сменить пароль</button>
                </div>
            </div>
            <div id="changePasswordDiv" class="row g-3 mt-1 d-none">
                <div class="col-sm-3">
                    <label class="form-label">Старый пароль</label>
                    <input type="password" class="form-control" name="oldPassword">
                    <label class="form-label">Новый пароль</label>
                    <input type="password" class="form-control" name="newPassword">
                    <label class="form-label">Подтверждение нового пароля</label>
                    <input type="password" class="form-control" name="newPasswordCheck">
                    <input type="submit" class="form-control mt-3 btn btn-success w-50" value="Сохранить"/>
                </div>
            </div>

            <h5 class="mt-4">Статистика книг</h5>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    {{--                    TODO: прикрутить стату--}}
                    <p>Отдано: 65</p>
                    <p>Взято: 73</p>
                </div>
            </div>
        </div>

        <!-- Contact info -->
        <div class="mb-4">
            <h5 class="mb-4">Контактная информация</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Номер телефона</label>
                    <div class="row">
                        <div class="col-5">
                            <input type="tel" class="form-control" id="ec-mobile-number"
                                   placeholder="+7 (987) 654-32-10"
                                   name="phone_number"
                                   value="{{$user->phone()->first()->number ?? ''}}"/>
                        </div>

                        <div class="col-sm-3">
                            <input type="submit" class="form-control btn btn-success" value="Сохранить"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
