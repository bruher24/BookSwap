<div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="modalAddBook">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
                <h1 class="fw-bold mb-0 fs-2">Добавить книгу</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-5 pt-0">
                <form id="addBookForm" method="post" action="{{ route('books.store') }}">
                    @csrf
                    <input class="mainInput" type="hidden" name="userId" value="{{ $user->id }}">
                    <div class="form-floating mb-3">
                        <input type="text" class="mainInput form-control rounded-3" name="name" id="floatingInput" required
                               placeholder="Мастер и маргарита">
                        <label for="floatingInput">Название</label>
                    </div>

                    <div class="form-floating mb-3 authorDiv">
                        <select class="mainInput form-control form-select rounded-3" name="authorId" id="floatingAuthorId">
                            <option selected disabled>Выберите автора...</option>
                        </select>
                        <label for="floatingAuthorId">Автор</label>
                    </div>

                    <button class="btn btn-sm btn-outline-secondary mb-3" type="button"
                            data-bs-toggle="collapse" data-bs-target="#authorFieldsCollapse"
                            aria-expanded="false" aria-controls="authorFieldsCollapse">
                        Создать нового автора
                    </button>

                    <button id="addAuthorBtn" class="btn btn-sm btn-outline-secondary mb-3" type="button" aria-expanded="false">
                        Добавить автора
                    </button>

                    <div class="collapse" id="authorFieldsCollapse">
                        <div class="card card-body mb-3">
                            <p class="text-muted mb-3"><small>Если не нашли нужного автора в списке</small></p>

                            <div class="form-floating mb-3">
                                <input type="text" class="additionalInput form-control rounded-3" name="authorLastname" id="floatingAuthorLastname"
                                       placeholder="Пушкин">
                                <label for="floatingAuthorLastname">Фамилия автора</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="additionalInput form-control rounded-3" name="authorFirstname" id="floatingAuthorFirstname"
                                       placeholder="Александр">
                                <label for="floatingAuthorFirstname">Имя автора</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="additionalInput form-control rounded-3" name="authorPatronymic" id="floatingAuthorPatronymic"
                                       placeholder="Сергеевич">
                                <label for="floatingAuthorPatronymic">Отчество автора (при наличии)</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" class="additionalInput form-control rounded-3" name="authorBirthdate" id="floatingAuthorBirthdate"
                                       placeholder="06.06.1799">
                                <label for="floatingAuthorBirthdate">Дата рождения автора</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" min="1" class="mainInput form-control rounded-3" name="pageCount" id="floatingPageCount" required
                               placeholder="501">
                        <label for="floatingPageCount">Кол-во страниц</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="file" class="mainInput form-control rounded-3" name="photo" id="floatingPhoto">
                        <label for="floatingPhoto">Фотография</label>
                    </div>

                    <button id="saveBookBtn" class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="button">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</div>
