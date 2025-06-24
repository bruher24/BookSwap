<header class="p-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="{{ route('home') }}"
               class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="#" alt="BookSwap">
            </a>
            <!--NAVBAR-->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li>
                    <a class="nav-link px-2 text-secondary"
                       href="{{ route('home') }}">
                        Главная
                    </a>
                </li>

                <li>
                    <a class="nav-link text-secondary dropdown-toggle" id="dropdownUser1"
                       data-bs-toggle="dropdown" aria-expanded="false"
                       style="cursor: pointer">
                        Каталог
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('books.index') }}">
                                Полный список книг
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('users.logout') }}">
                                Жанры
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('users.logout') }}">
                                Авторы
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a class="nav-link px-2 text-secondary"
                       href="{{ route('users.books', ['user' => auth()->id()]) }}">
                        Мои книги
                    </a>
                </li>

                <li>
                    <a class="nav-link px-2 text-secondary"
                       href="{{ route('about') }}">
                        О нас
                    </a>
                </li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                <input type="search" class="form-control form-control-dark text-bg-white" placeholder="Поиск..."
                       aria-label="Search">
            </form>
            @guest
                <div class="text-end">
                    <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal"
                            data-bs-target="#modalLogin">Войти
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalSignin">
                        Регистрация
                    </button>
                </div>
            @endguest
            @auth
                <div class="dropdown text-end">
                    <a class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser1"
                       data-bs-toggle="dropdown" aria-expanded="false"
                       style="cursor: pointer">
                        <img src="{{ isset(auth()->user()->photo) ? auth()->user()->photo->src : '/storage/avatar.png'}}"
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
            @endauth
        </div>
    </div>
</header>
