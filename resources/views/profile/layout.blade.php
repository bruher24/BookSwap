@extends('layout')
@section('title')
    Личный кабинет
@endsection
@section('main')
    <!-- Main Content -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="row g-0" style="min-height: 500px">
                    <!-- Sidebar -->
                    <div class="col-lg-3 border-end">
                        <div class="p-4">
                            <div class="nav flex-column nav-pills">
                                <a class="nav-link
                                    @if(request()->is('users/profile/personal') || request()->is('users/profile'))
                                        text-light text-bg-dark
                                    @else
                                        text-dark
                                    @endif"
                                   href="{{ route('users.profile', ['section' => 'personal']) }}">
                                    <i class="fas fa-user me-2"></i>
                                    Личные данные
                                </a>
                                <a class="nav-link
                                    @if(request()->is('users/profile/settings'))
                                        text-light text-bg-dark
                                    @else
                                        text-dark
                                    @endif"
                                   href="{{ route('users.profile', ['section' => 'settings']) }}">
                                    <i class="fas fa-bell me-2"></i>
                                    Настройки
                                </a>
                            </div>
                        </div>
                        <hr class="hr"/>
                        <!-- Recent Activity -->
                        {{--                        <div class="ps-4 ms-3">--}}
                        {{--                            <h5 class="mb-4">Recent Activity</h5>--}}
                        {{--                            <div class="activity-item mb-3">--}}
                        {{--                                <h6 class="mb-1">Updated profile picture</h6>--}}
                        {{--                                <p class="text-muted small mb-0">2 hours ago</p>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="activity-item mb-3">--}}
                        {{--                                <h6 class="mb-1">Changed password</h6>--}}
                        {{--                                <p class="text-muted small mb-0">Yesterday</p>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="activity-item">--}}
                        {{--                                <h6 class="mb-1">Updated billing information</h6>--}}
                        {{--                                <p class="text-muted small mb-0">3 days ago</p>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>

                    <!-- Content Area -->
                    <div class="col-lg-9">
                        <div class="p-4">
                            @yield('profile.main')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
