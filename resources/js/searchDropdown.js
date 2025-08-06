import * as utils from "./utils.js";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

let $form = $('#searchForm');
$form[0].reset();
let $search = $('#searchInput');

$search.on('input', e => {
    e.preventDefault();
    $('#searchDropdown').remove();
    searchRequest(e.target.value)
        .then(response => {
            let $output = $('<ul>', {
                id: 'searchDropdown',
                class: 'searchElement dropdown-menu d-grid p-2 rounded-3 mx-0 border-0 shadow w-220px'
            });
            $output.data('bs-theme', 'light');

            let found = response.data.found;

            if ($.isEmptyObject(found)) {
                $output.append('<a class="searchElement nav-link">Ничего не найдено</a>');
                $form.append($output);
                return;
            }

            let filtered = {};
            for (let [key, item] of Object.entries(found)) {
                filtered[key] = item.length > 3 ? item.slice(0, 3) : item;
            }

            for (const [category, content] of Object.entries(filtered)) {
                let categoryName = getCategoryName(category);
                $output.append(`<li class="searchElement text-secondary"><small class="searchElement">${categoryName}</small></li>`);
                content.forEach(function (element) {
                    let link = `/${category}/${element.id}`;
                    let name = element.name;
                    if (element.formattedName) {
                        link += '/books';
                        name = element.formattedName;
                    }
                    $output.append(`<li class="searchElement"><a class="dropdown-item rounded-2" href="${link}">${name}</a></li>`);
                });
                $output.append('<hr class="searchElement dropdown-divider">');
            }
            // TODO: добавить страницу / всплывающее окно / другое и отобразить там все результаты поиска
            $output.append(`<li class="searchElement"><a class="dropdown-item rounded-2 small" href="${1}">Все результаты `
                + `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">`
                + `<path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg></a></li>`);
            $output.children('hr').last().remove();
            $form.append($output);
        })
        .catch(e => {
            utils.showAlert('Ошибка при поиске');
        });
});

$(document).on('click', function (e) {
    if (!$(e.target).hasClass('searchElement')) {
        $('#searchDropdown').detach();
    }
});


async function searchRequest(query) {
    return await window.axios.get(`/search?query=${query}`);
}

function getCategoryName(category) {
    switch (category) {
        case 'books':
            return 'Книги';
        case 'authors':
            return 'Авторы';
    }
}
