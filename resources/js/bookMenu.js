$('.book-card').on('contextmenu', function (e) {
    e.preventDefault();

    $('.custom-context-menu').remove();
    const bookId = $(this).data('book');
    const menu = $(
        `<ul class="custom-context-menu dropdown-menu show position-fixed" 
                style="display: block; z-index: 1000;">
                <li><button data-copy="localhost:3000/books/${bookId}" id="shareBookBtn" class="dropdown-item" >Поделиться</button></li>
                <li><button id="editBookBtn" class="bookBtn link dropdown-item">Изменить книгу</button></li>
                <li><button id="deleteBookBtn" class="link-danger dropdown-item">Удалить книгу</button></li>
            </ul>`
    );

    menu.css({
        'left': e.pageX + 'px',
        'top': e.pageY + 'px'
    });

    $('body').append(menu);

    $(document).on('click.contextmenu', function (e) {
        if (!$(e.target).closest('.custom-context-menu').length) {
            menu.remove();
            $(document).off('click.contextmenu');
        }
    });

    $('#editBookBtn').on('click', function () {
        $('#modalTitle').html('Изменить книгу');
        $('#modalBookForm').modal('show');
        fillBookData(bookId);
    });

    $('#modalBookForm').on('show.bs.modal', function () {
        menu.remove();
        $(document).off('click.contextmenu');
    });

    $('#deleteBookBtn').on('click', function () {
        menu.remove();
        $(document).off('click.contextmenu');
        if (confirm('Вы уверены, что хотите удалить эту книгу?')) {
            deleteBook(bookId).then(function (response) {
                if (response.data.success === true) {
                    window.location.reload();
                    showAlert('success', 'Книга успешно удалена!');
                } else {
                    showAlert('danger', 'Ошибка при удалении книги!');
                }
            });
        }
    });

    $('#shareBookBtn').on('click', async function () {
        await navigator.clipboard.writeText($(this).data('copy'));
        menu.remove();
        $(document).off('click.contextmenu');

        const toastEl = $(`
    <div class="toast align-items-center text-white bg-success border-0" 
         role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i> Ссылка скопирована!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
`);

        const toastContainer = $('<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 1100;"></div>')
            .append(toastEl)
            .appendTo('body');

        const toast = new bootstrap.Toast(toastEl[0]);

        toast.show();

        setTimeout(() => {
            toast.hide();
            toastEl.on('hidden.bs.toast', function () {
                toastContainer.remove();
            });
        }, 3000);
    });
});


function fillBookData(bookId) {
    getBookData(bookId).then(function (bookData) {
        const book = bookData.data.book;
        console.log(book);
        $('#floatingBookName').val(book.name);
        $('#floatingTypeId').val(book.type_id);

        const authors = book.authors;
        for (let i = 0; i < authors.length; i++) {
            let authorId = '#floatingAuthorId' + (i === 0 ? '' : `_${i}`);
            $(authorId).val(authors[i].id);
            if (authors.length - i > 1) {
                $('#addAuthorBtn').click();
            }
        }
        $('#floatingPageCount').val(book.page_count);
        $('#floatingPublishingHouse').val(book.publishing_house);
        $('#floatingPublicationYear').val(book.publication_year);
        $('#floatingIsbn').val(book.isbn);
    });
}

async function getBookData(bookId) {
    try {
        const response = await $.ajax({
            url: `/books/${bookId}`,
            type: 'get',
            async: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        });
        return {
            success: response.success,
            data: response,
        };
    } catch (error) {
        console.error('Ошибка:', error);
        return {
            success: false,
            errors: error.responseJSON?.errors || {error: [error.responseJSON?.message || 'Произошла ошибка']}
        };
    }
}

async function deleteBook(bookId) {
    const token = $('input[name="_token"]').val();
    try {
        const response = await $.ajax({
            url: `/books/${bookId}`,
            type: 'delete',
            async: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: {
                _token: token,
            },
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
