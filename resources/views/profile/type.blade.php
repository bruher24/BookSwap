@extends('profile.layout')
@section('profile.main')
    <!-- Settings Cards -->
    <div class="mb-4">
        <h5 class="mb-4">Тип аккаунта</h5>
        <h1>убрать оно вообще</h1>
{{--    TODO: тут вообще все перелопатить надо    --}}
        <form method="post" action="{{url("user/switch_type")}}">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="settings-card card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Аккаунт врача</h6>
                                    <p class="text-muted mb-0 small">Аккаунт, который используется сотрудником</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="doctor_check" name="doctor_check"
                                        @checked($role === 'doctor' || $role === 'admin')>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
{{--            --}}
            @if($role === 'doctor' || $role === 'admin')
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="specialty" class="form-label">Специальность</label>
                            <!--                    <input type="text" class="form-control" id="specialty" name="specialty" placeholder="Введите вашу специальность">-->
                            <select type="text" class="form-control" id="specialty" name="specialty">
                                <option selected>
                                    Выберите вашу специальность...
                                </option>
                                @foreach($specialities as $speciality)
                                    <option @selected($user->doctor->speciality->first()->name == $speciality->name)>
                                        {{ $speciality->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row g-3 ">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="experience" class="form-label">Стаж (лет)</label>
                            <input type="number" class="form-control" id="experience" name="experience" value="{{ $user->doctor->stage }}" placeholder="Укажите ваш стаж" min="0">
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-1">
                        <input type="button" class="form-control btn btn-success"
                               id="save_type_btn"
                               value="Сохранить"/>
                    </div>
                </div>
            @endif
{{--            --}}

        </form>
    </div>
@endsection
