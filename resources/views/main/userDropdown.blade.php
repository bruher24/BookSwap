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
            <a class="dropdown-item"
               href="{{ route('users.profile') }}">
                Личный кабинет
            </a>
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('users.profile', ['section' => 'settings']) }}">
                Настройки
            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('users.logout') }}">
                Выйти
            </a>
        </li>
    </ul>
</div>