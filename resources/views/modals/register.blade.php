<div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
                <h1 id="modalRegisterTitle" class="fw-bold mb-0 fs-2">Регистрация</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-5 pt-0">
                <form method="post" action="{{ route('users.create') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" name="name" id="floatingName"
                               placeholder="Имя" max="50">
                        <label for="floatingName">Имя</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control rounded-3" name="email" id="floatingEmail"
                               placeholder="name@example.com">
                        <label for="floatingEmail">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control rounded-3" name="password" id="floatingPassword"
                               placeholder="Password">
                        <label for="floatingPassword">Пароль</label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" name="remember" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Запомнить меня
                        </label>
                    </div>

                    <button class="authorize-btn w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">
                        Зарегистрироваться
                    </button>

                    <small class="text-body-secondary">
                        Нажимая, вы соглашаетесь с <a href="#">Пользовательским
                            соглашением</a> и <a href="#">Политикой конфиденциальности</a>.
                    </small>

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
