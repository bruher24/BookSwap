$(function () {
    // TODO: прикрутить реал тайм поиск (по вводу каждого символа)
    let $form = $('#searchForm');
    $form[0].reset();
    $form.on('submit', function (e) {
        e.preventDefault();
        $('#searchDropdown').remove();
        let $search = $('#searchInput');
        searchRequest($search.val()).then(function (response) {
            if (response.success) {
                let $output = $('<ul>', {
                    id: 'searchDropdown',
                    class: 'searchElement dropdown-menu d-grid p-2 rounded-3 mx-0 border-0 shadow w-220px'
                });
                $output.data('bs-theme', 'light');

                if ($.isEmptyObject(response.data)) {
                    $output.append('<a class="searchElement nav-link">Ничего не найдено</a>');
                    $form.append($output);
                    return;
                }

                let filtered = {};
                for (let [key, item] of Object.entries(response.data)) {
                    filtered[key] = item.length > 3 ? item.slice(0, 3) : item;
                }

                for (const [category, content] of Object.entries(filtered)) {
                    let categoryName = getCategoryName(category);
                    $output.append(`<li class="searchElement text-secondary"><small class="searchElement">${categoryName}</small></li>`);
                    content.forEach(function (element) {
                        let link = category + '/' + element.id;
                        let name = element.name;
                        if (element.formattedName) {
                            link += '/books';
                            name = element.formattedName;
                        }
                        $output.append(`<li class="searchElement"><a class="dropdown-item rounded-2" href="${link}">${name}</a></li>`);
                    });
                    $output.append('<hr class="searchElement dropdown-divider">');
                }
                $output.children('hr').last().remove();
                $form.append($output);
            }
        });
    });

    $(document).on('click', function (e) {
        if (!$(e.target).hasClass('searchElement')) {
            $('#searchDropdown').detach();
        }
    });
});

async function searchRequest(query) {
    return await $.ajax({
        url: `/search`,
        type: 'get',
        async: true,
        data: {
            query: query
        }
    });
}

function getCategoryName(category) {
    switch (category) {
        case 'books':
            return 'Книги';
        case 'authors':
            return 'Авторы';
    }
}
