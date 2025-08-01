import * as utils from "./utils.js";

$('.book-card').on('contextmenu', function (e) {
    e.preventDefault();

    $('.custom-context-menu').remove();
    const bookId = $(this).data('book');
    const menu = $(
        `<ul class="custom-context-menu dropdown-menu show position-fixed" 
                style="display: block; z-index: 1000;">
                <li>
                    <button data-copy="${window.location.origin}/books/${bookId}" id="shareBookBtn" class="dropdown-item" >
                        Поделиться
                    </button>
                </li>
                <li>
                    <button id="editBookBtn" class="bookBtn link dropdown-item">
                        Изменить книгу
                    </button>
                </li>
                <li>
                    <button id="deleteBookBtn" class="link-danger dropdown-item">
                        Удалить книгу
                    </button>
                </li>
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
                    utils.showAlert('success', 'Книга успешно удалена!');
                } else {
                    utils.showAlert('danger', 'Ошибка при удалении книги!');
                }
            });
        }
    });

    $('#shareBookBtn').on('click', async function () {
        await utils.showFadeAlert($(this).data('copy'), 'Ссылка скопирована!');
        menu.remove();
        $(document).off('click.contextmenu');
    });
});


function fillBookData(bookId) {
    getBookData(bookId).then(function (response) {
        const book = response.data;
        $('#floatingBookName').val(book.name);
        $('#floatingTypeId').val(book.book_type);

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
    return await $.ajax({
        url: `/api/v1/books/${bookId}`,
        type: 'get',
        async: true,
    });
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
        return {
            success: false,
            errors: error.responseJSON?.errors || {error: [error.responseJSON?.message || 'Произошла ошибка']}
        };
    }
}
