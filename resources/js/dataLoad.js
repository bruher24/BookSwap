import * as utils from "./utils.js";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

let authors = [];
let types = [];

loadAuthorsToSelect();
loadTypesToSelect();
$('.bookBtn').on('click', () => {
    loadAuthorsToSelect();
    loadTypesToSelect();
});


function loadAuthorsToSelect(selectId = '#floatingAuthorId') {
    authorsListRequest()
        .then(response => {
            authors = response.data.authors;
            const $select = $(selectId);
            $select.empty().append('<option selected value="0">Выберите автора...</option>');

            authors.forEach((author) => {
                const option = `<option value="${author.id}">${author.fullName}</option>`;
                $select.append(option);
            });
        })
        .catch(e => {
            if (e.response.data.message) {
                utils.showAlert('danger', e.response.data.message);
            }
        });
}

function loadTypesToSelect(selectId = '#floatingBookType') {
    typesListRequest()
        .then(response => {
            types = response.data.booktypes;
            const $select = $(selectId);
            $select.empty().append('<option selected value="0">Выберите тип...</option>');

            for (const [key, type] of Object.entries(types)) {
                const option = `<option value="${key}">${type}</option>`;
                $select.append(option);
            }
        })
        .catch(e => {
            if (e.response.data.message) {
                utils.showAlert('danger', e.response.data.message);
            }
        });
}

async function authorsListRequest() {
    return await window.axios.get('/api/v1/authors');
}

async function typesListRequest() {
    return await window.axios.get('/api/v1/types');
}
