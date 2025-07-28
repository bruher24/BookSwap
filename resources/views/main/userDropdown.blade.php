<div class="dropdown text-end">
    <a class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser1"
       data-bs-toggle="dropdown" aria-expanded="false"
       style="cursor: pointer">
        <img src="{{ Storage::disk('public')->url(isset($user) ? $user->photo->src : Photo::$basePhotoName) }}"
             alt="Avatar" width="40"
             height="40"
             class="rounded-circle">
    </a>
    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
        <li>
            <a class="dropdown-item  btn btn-dark btn btn-dark"
               href="{{ route('users.profile') }}">
                {{ $user->name }}
            </a>
        </li>

        <li>
            <hr class="dropdown-divider my-1">
        </li>

        <li>
            <a class="dropdown-item  btn btn-dark position-relative"
               href="{{ route('users.notifications', ['user' => $user]) }}">
                Уведомления
                @if(isset($user->notifications) && !$user->notifications->isEmpty())
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    +{{ $user->notifications->count() }}
                    <span class="visually-hidden">unread notifications</span>
                </span>
                @endif
            </a>
        </li>

        <li>
            <a class="dropdown-item  btn btn-dark"
               href="{{ route('users.chat', ['user' => $user]) }}">
                Сообщения
                @if(isset($user->messages) && !$user->messages->isEmpty())
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    +{{ $user->messages->count() }}
                    <span class="visually-hidden">unread messages</span>
                </span>
                @endif
            </a>
        </li>

        <li>
            <a class="dropdown-item  btn btn-dark"
               href="{{ route('users.logout') }}">
                Выйти
            </a>
        </li>
    </ul>
</div>