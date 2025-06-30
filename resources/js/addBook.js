$(function () {
    let selectedAuthors = [];

    $('#addBookBtn').click(function () {
        $('#modalTitle').html('Добавить книгу');
        $('#bookForm')[0].reset();
    });

    // Делегирование событий для ВСЕХ select (включая динамически добавленные)
    $(document).on('focus', '.authorDiv select', function () {
        $(this).data('old-value', $(this).val()); // Сохраняем текущее значение
    }).on('change', '.authorDiv select', function () {
        const $select = $(this);
        const newValue = $select.val();
        const oldValue = $select.data('old-value');

        // Проверка на дубликат
        if (newValue && newValue !== '0' && selectedAuthors.includes(newValue)) {
            alert('Этот автор уже выбран в другом поле!');
            $select.val(oldValue === null ? '0' : oldValue);
            return;
        }

        // Обновляем массив выбранных авторов
        if (oldValue && oldValue !== '0') {
            selectedAuthors = selectedAuthors.filter(id => id !== oldValue);
        }
        if (newValue && newValue !== '0') {
            selectedAuthors.push(newValue);
        }
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
        const newName = 'author_id' + +$authorDivs.length;
        $newDiv.find('select')
            .attr('id', newId)
            .attr('name', newName)
            .val('0'); // Сбрасываем выбранное значение

        $newDiv.find('label')
            .attr('for', newId)
            .text('Дополнительный автор');

        $lastAuthorDiv.after($newDiv);
    });

    $('#saveBookBtn').click(async function () {
        const form = $('#bookForm');
        const formData = getFormData(form);
        console.log(formData);
        const result = await storeBookRequest(formData);

        if (result.success === true) {
            showAlert('success', 'Книга успешно сохранена!');
            // Дополнительные действия при успехе
            $('#modalBookForm').modal('hide');
            form[0].reset();
        } else {
            showAlert('danger', 'Ошибка при сохранении книги');
            // Вывод ошибок валидации
            if (result.errors) {
                showValidationErrors(result.errors);
            }
        }
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
    for (const [key, value] of Object.entries(formData)) {
        if (value === '0' || value === null) {
            delete formData[key];
        }
        if (!$('#authorFieldsCollapse').hasClass('show')) {
            delete formData.authorFirstname;
            delete formData.authorLastname;
            delete formData.authorPatronymic;
            delete formData.authorBirthdate;
        }
    }
    return formData;
}

function inputsCallback(selector = '.mainInput', callback) {
    let inputs = $(selector);
    inputs.each(function () {
        let property = this.name;
        let val = this.value;
        callback(property, val, $(this));
    });
}

async function storeBookRequest(data) {

    data.publishing_house = 'test';
    data.publication_year = '2002';
    data.type_id = 1;

    try {
        const response = await $.ajax({
            url: '/books/store',
            type: 'post',
            async: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: data,
        });

        return {
            success: true,
            data: response
        };
    } catch (error) {
        console.error('Ошибка:', error);
        return {
            success: false,
            errors: error.responseJSON?.errors || {error: [error.responseJSON?.message || 'Произошла ошибка']}
        };
    }
}

// Показ alert-уведомления
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('#ajaxAlerts').append(alertHtml);

    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
}

// Показ ошибок валидации
function showValidationErrors(errors) {
    // Очищаем предыдущие ошибки
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    // Добавляем новые ошибки
    for (const [field, messages] of Object.entries(errors)) {
        const input = $(`[name="${field}"]`);
        if (input.length) {
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback">${messages.join(', ')}</div>`);
        } else {
            // Для ошибок без конкретного поля
            showAlert('danger', messages.join(', '));
        }
    }
}
