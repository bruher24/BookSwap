<div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="modalLogin">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
                <h1 id="modalLoginTitle" class="fw-bold mb-0 fs-2">Авторизация</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-5 pt-0">
                <form class="" method="post" action="{{ route('users.login') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control rounded-3" name="email" id="floatingEmailLogin"
                               placeholder="name@example.com">
                        <label for="floatingEmailLogin">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control rounded-3" name="password" id="floatingPasswordLogin"
                               placeholder="Password">
                        <label for="floatingPasswordLogin">Пароль</label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" name="remember" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Запомнить меня
                        </label>
                    </div>

                    <button class="authorize-btn w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">
                        Войти
                    </button>

                    <hr class="my-4">

                    <h2 class="fs-5 fw-bold mb-3">
                        или войдите через соцсети:
                    </h2>

                    <button class="w-100 py-2 mb-2 btn btn-outline-primary rounded-3" type="submit">
                        <svg class="bi me-1" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#google"></use>
                        </svg>
                        Войти через Google
                    </button>
                    <button class="w-100 py-2 mb-2 btn btn-outline-primary rounded-3" type="submit">
                        <svg class="bi me-1" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#vk"></use>
                        </svg>
                        Войти через VK
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
