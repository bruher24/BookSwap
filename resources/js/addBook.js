import * as utils from "./utils.js";

$(function () {
    let selectedAuthors = [];

    $('#addBookBtn').click(function () {
        $('#modalBookTitle').html('Добавить книгу');
        $('#bookForm')[0].reset();
    });

    $(document).on('focus', '.authorDiv select', function () {
        $(this).data('old-value', $(this).val());
    }).on('change', '.authorDiv select', function () {
        $(this).blur();
        const $select = $(this);
        const newValue = $select.val();
        const oldValue = $select.data('old-value');

        if (newValue && newValue !== '0' && selectedAuthors.includes(newValue)) {
            alert('Этот автор уже выбран в другом поле!');
            $select.val(oldValue === null ? '0' : oldValue);
            return;
        }

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

        const $lastAuthorDiv = $authorDivs.last();
        const $newDiv = $lastAuthorDiv.clone();

        const newId = 'floatingAuthorId_' + $authorDivs.length;
        const newName = 'author_id' + +$authorDivs.length;
        $newDiv.find('select')
            .attr('id', newId)
            .attr('name', newName)
            .val('0');

        $newDiv.find('label')
            .attr('for', newId)
            .text('Дополнительный автор');

        $lastAuthorDiv.after($newDiv);
    });

    $('#saveBookBtn').click(function () {
        const form = $('#bookForm');
        const formData = getFormData(form);
        storeBookRequest(formData).then(function (result) {
            if (result.success === true) {
                utils.showAlert('success', 'Книга успешно сохранена!');
                $('#modalBookForm').modal('hide');
                form[0].reset();
                window.location.reload();
            } else {
                utils.showAlert('danger', 'Ошибка при сохранении книги!');
                if (result.errors) {
                    showValidationErrors(result.errors);
                }
            }
        });
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

async function storeBookRequest(data) {
    return await $.ajax({
        url: '/books/store',
        type: 'post',
        async: true,
        data: data,
    });
}

function showValidationErrors(errors) {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    for (const [field, messages] of Object.entries(errors)) {
        const input = $(`[name="${field}"]`);
        if (input.length) {
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback">${messages.join(', ')}</div>`);
        } else {
            utils.showAlert('danger', messages.join(', '));
        }
    }
}

// Unused service function
function inputsCallback(selector = '.mainInput', callback) {
    let inputs = $(selector);
    inputs.each(function () {
        let property = this.name;
        let val = this.value;
        callback(property, val, $(this));
    });
}
