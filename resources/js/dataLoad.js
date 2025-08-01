let authors = [];
let types = [];

$(function () {
    loadAuthorsToSelect();
    loadTypesToSelect();
    $('.bookBtn').on('click', function () {
        loadAuthorsToSelect();
        loadTypesToSelect();
    });
});

function loadAuthorsToSelect(selectId = '#floatingAuthorId') {
    authorsListRequest().then(function (response) {
        if (response.success) {
            authors = response.authors;
            const $select = $(selectId);
            $select.empty().append('<option selected value="0">Выберите автора...</option>');

            authors.forEach((author) => {
                const option = `<option value="${author.id}">${author.fullName}</option>`;
                $select.append(option);
            });
        }
    });
}

function loadTypesToSelect(selectId = '#floatingBookType') {
    typesListRequest().then(function (response) {
        if (response.success) {
            types = response.booktypes;
            const $select = $(selectId);
            $select.empty().append('<option selected value="0">Выберите тип...</option>');

            for (const [key, type] of Object.entries(types)) {
                const option = `<option value="${key}">${type}</option>`;
                $select.append(option);
            }
        }
    });
}

async function authorsListRequest() {
    return await $.ajax({
        url: '/api/v1/authors/',
        type: 'get',
        async: true,
    });
}

async function typesListRequest() {
    return await $.ajax({
        url: '/api/v1/types/',
        type: 'get',
        async: true,
    });
}
