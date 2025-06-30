let authors = [];

$(function () {
    loadAuthorsToSelect();

    $('.bookBtn').on('click', function () {
        loadAuthorsToSelect();
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

async function authorsListRequest() {
    return await $.ajax({
        url: '/authors/',
        type: 'get',
        async: true,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    });
}