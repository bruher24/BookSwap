import * as utils from "./utils.js";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

let selectedAuthors = [];

$('#addBookBtn').click(() => {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    $('#modalBookTitle').html('Добавить книгу');
    $('#bookForm')[0].reset();
    $('input[name="book_id"]').val('');
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

$('#addAuthorBtn').click(() => {
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

$('#saveBookBtn').click(() => {
    const form = $('#bookForm');
    let formData = new FormData(form[0]);
    formData = formatData(formData);

    storeBookRequest(formData)
        .then(response => {
            utils.showAlert('success', 'Книга успешно сохранена!');
            $('#modalBookForm').modal('hide');
            form[0].reset();
            window.location.reload();
        })
        .catch(e => {
            utils.showAlert('danger', 'Ошибка при сохранении книги');
            if (e.response.data.errors) {
                showValidationErrors(e.response.data.errors);
            }
        });
});

function formatData(formData) {
    for (const [key, value] of formData.entries()) {
        if (value === '0' || value === null || value === '') {
            formData.delete(key);
        }

        if (!$('#authorFieldsCollapse').hasClass('show')) {
            formData.delete('authorFirstname');
            formData.delete('authorLastname');
            formData.delete('authorPatronymic');
            formData.delete('authorBirthdate');
        }

        if (formData.get('cover') && formData.get('cover').size === 0) {
            formData.delete('cover');
        }
    }
    return formData;
}

async function storeBookRequest(data) {
    if (data.has('book_id')) {
        data.append('_method', 'PUT');
        return await window.axios.post(`/books/${data.get('book_id')}`, data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }

    return await window.axios.post('/books/store', data);
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
