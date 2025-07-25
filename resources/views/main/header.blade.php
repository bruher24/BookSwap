@php use App\Models\Photo;use Illuminate\Support\Facades\Storage; @endphp
<header class="p-3 text-bg-dark to-blur-node">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="{{ route('home') }}"
               class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none" style="margin-right: 8px">
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
                    <a class="nav-link text-secondary dropdown-toggle" id="dropdownCatalog1"
                       data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                       style="cursor: pointer; padding: 8px;">
                        Каталог
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownCatalog1">
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('books.index') }}">
                                Полный список книг
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('genres.index') }}">
                                Жанры
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('authors.index') }}">
                                Авторы
                            </a>
                        </li>
                    </ul>
                </li>

                @auth
                    <li>
                        <a class="nav-link px-2 text-secondary"
                           href="{{ route('users.books', ['user' => auth()->id()]) }}">
                            Мои книги
                        </a>
                    </li>
                @endauth

                <li>
                    <a class="nav-link px-2 text-secondary"
                       href="{{ route('about') }}">
                        О нас
                    </a>
                </li>
            </ul>

            <form id="searchForm"
                  class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                <input id="searchInput" type="text" class="form-control form-control-dark text-bg-white"
                       placeholder="Поиск..."
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
                @include('main.userDropdown')
            @endauth
        </div>
    </div>
</header>
