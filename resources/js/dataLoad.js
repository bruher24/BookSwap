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
        authors = JSON.parse(response);
        const $select = $(selectId);
        $select.empty().append('<option selected value="0">Выберите автора...</option>');

        authors.forEach((author) => {
            const option = `<option value="${author.id}">${author.fullName}</option>`;
            $select.append(option);
        });
    });
}

function loadTypesToSelect(selectId = '#floatingTypeId') {
    typesListRequest().then(function (response) {
        types = JSON.parse(response);
        const $select = $(selectId);
        $select.empty().append('<option selected value="0">Выберите тип...</option>');

        types.forEach((type) => {
            const option = `<option value="${type.id}">${type.name}</option>`;
            $select.append(option);
        });
    });
}

async function authorsListRequest() {
    return await $.ajax({
        url: '/api/v1/authors/',
        type: 'get',
        async: true,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    });
}

async function typesListRequest() {
    return await $.ajax({
        url: '/api/v1/types/',
        type: 'get',
        async: true,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    });
}
