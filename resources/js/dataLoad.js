window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

let authors = [];
let types = [];

loadAuthorsToSelect();
loadTypesToSelect();
$('.bookBtn').on('click', function () {
    loadAuthorsToSelect();
    loadTypesToSelect();
});


function loadAuthorsToSelect(selectId = '#floatingAuthorId') {
    authorsListRequest().then(response => {
        if (response.data.success) {
            authors = response.data.authors;
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
    typesListRequest().then(response => {
        if (response.data.success) {
            types = response.data.booktypes;
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
    return await window.axios.get('/api/v1/authors');
}

async function typesListRequest() {
    return await window.axios.get('/api/v1/types');
}
