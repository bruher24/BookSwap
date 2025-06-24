@extends('profile.layout')
@section('profile.main')
    <!-- Settings Cards -->
    <div class="mb-4">
        <h5 class="mb-4">Настройки</h5>
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="settings-card card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Two-Factor Authentication</h6>
                                <p class="text-muted mb-0 small">Add an extra layer of
                                    security</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="settings-card card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Email Notifications</h6>
                                <p class="text-muted mb-0 small">Receive activity updates
                                </p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
