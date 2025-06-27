import $ from 'jquery';

$(function () {
    let authors = [];
    let selectedAuthors = [];
    let oldValue = 'Выберите автора...'; // Вынесено в область видимости модуля

    $('#addBookBtn').click(function () {
        authorsListRequest().then(function (response) {
            authors = JSON.parse(response);
            console.log(authors);
            authors.forEach((author) => {
                const option = `<option value="${author.id}">${author.fullName}</option>`;
                $('#floatingAuthorId').append(option);
            });
        });
    });

    // Делегирование событий для ВСЕХ select (включая динамически добавленные)
    $(document)
        .on('focus', '.authorDiv select', function() {
            $(this).data('old-value', $(this).val()); // Сохраняем текущее значение
        })
        .on('change', '.authorDiv select', function() {
            const $select = $(this);
            const newValue = $select.val();
            const oldValue = $select.data('old-value');

            // Проверка на дубликат
            if (newValue && newValue !== 'Выберите автора...' && selectedAuthors.includes(newValue)) {
                alert('Этот автор уже выбран в другом поле!');
                $select.val(oldValue === null ? 'Выберите автора...' : oldValue);
                return;
            }

            // Обновляем массив выбранных авторов
            if (oldValue && oldValue !== 'Выберите автора...') {
                selectedAuthors = selectedAuthors.filter(id => id !== oldValue);
            }
            if (newValue && newValue !== 'Выберите автора...') {
                selectedAuthors.push(newValue);
            }

            console.log('Выбранные авторы:', selectedAuthors);
        });

    $('#addAuthorBtn').click(function () {
        const $authorDivs = $('.authorDiv');
        if ($authorDivs.length >= 3) {
            alert('Добавлен максимум авторов!');
            return;
        }

        // Клонируем последний select и очищаем его значение
        const $lastAuthorDiv = $authorDivs.last();
        const $newDiv = $lastAuthorDiv.clone();

        // Генерируем новый ID для select и label
        const newId = 'floatingAuthorId_' + $authorDivs.length;
        const newName = 'authorId' + +$authorDivs.length;
        $newDiv.find('select')
            .attr('id', newId)
            .attr('name', newName)
            .val('Выберите автора...'); // Сбрасываем выбранное значение

        $newDiv.find('label')
            .attr('for', newId)
            .text('Дополнительный автор');

        $lastAuthorDiv.after($newDiv);
    });

    $('#saveBookBtn').click(function () {
        const form = $('#addBookForm');
        const formData = getFormData(form);
        console.log(formData);
    });
});


function getFormData(form) {
    const formData = form.serializeArray().reduce(function (obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});

    return formatData(formData);
}

function formatData(formData) {
    for (const [value, key] of Object.entries(formData)) {
        console.log(value, key);
    }
    return formData;
}

function mainInputs(callback) {
    let selector = '.mainInput';
    let inputs = $(selector);

    inputs.each(function () {
        let property = this.name;
        let val = this.value;
        callback(property, val);
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