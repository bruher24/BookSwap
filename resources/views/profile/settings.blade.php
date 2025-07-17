@extends('profile.layout')
@section('profile.main')
    <!-- Settings Cards -->
    <div class="mb-4">
        <h5 class="mb-4">Настройки</h5>

        <div class="row g-4 mb-4">
            @foreach($settings as $setting)
                <div class="col-sm-3">
                    <div class="settings-card card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $setting->label }}</h6>
                                    <p class="text-muted mb-0 small">{{ $setting->description }}</p>
                                </div>
                                <form class="form-check form-switch" method="post"
                                      action="{{ route('users.updateSettings', ['user' => $user]) }}">
                                    @method('patch')
                                    @csrf
                                    <input type="hidden" name="setting_name" value="{{ $setting->name }}">
                                    <input class="form-check-input" type="checkbox"
                                           name="setting_value"
                                           onchange="this.form.submit()"
                                            {{--                                           TODO: убрать логику--}}
                                            @checked($user->settings()->where('name', $setting->name)->first() &&
    $user->settings()->where('name', $setting->name)->first()->pivot->value == 'on')
                                    >
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
