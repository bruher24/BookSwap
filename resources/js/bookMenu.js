import * as utils from "./utils.js";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

$('.book-card').on('contextmenu', function (e) {
    e.preventDefault();

    $('.custom-context-menu').remove();
    const bookId = $(this).data('book');
    let menu = `<ul class="custom-context-menu dropdown-menu show position-fixed" 
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
                </li>`;

    let regex = /\/users\/\d*\/books/;
    let isUserLib = regex.test(window.location.pathname);
    if (isUserLib) {
        menu += `<li>
                    <button id="deleteBookBtn" class="link-danger dropdown-item">
                        Удалить книгу
                    </button>
                </li>`;
    }
    menu += `</ul>`;

    const $menu = $(menu);

    $menu.css({
        'left': e.pageX + 'px',
        'top': e.pageY + 'px'
    });

    $('body').append($menu);

    $(document).on('click.contextmenu', function () {
        if (!$(this).closest('.custom-context-menu').length) {
            $menu.remove();
            $(document).off('click.contextmenu');
        }
    });

    $('#editBookBtn').on('click', () => {
        $('#modalTitle').html('Изменить книгу');
        $('#modalBookForm').modal('show');
        fillBookData(bookId);
    });

    $('#modalBookForm').on('show.bs.modal', () => {
        $menu.remove();
        $(document).off('click.contextmenu');
    });

    $('#deleteBookBtn').on('click', () => {
        $menu.remove();
        $(document).off('click.contextmenu');
        if (confirm('Вы уверены, что хотите удалить эту книгу?')) {
            deleteBook(bookId)
                .then(response => {
                    window.location.reload();
                    utils.showAlert('success', 'Книга успешно удалена!');
                })
                .catch(e => {
                    utils.showAlert('danger', 'Ошибка при удалении книги!');
                });
        }
    });

    $('#shareBookBtn').on('click', async function () {
        await utils.showFadeAlert($(this).data('copy'), 'Ссылка скопирована!');
        $menu.remove();
        $(document).off('click.contextmenu');
    });
});


function fillBookData(bookId) {
    getBookData(bookId)
        .then(response => {
            const book = response.data.book;
            $('#floatingBookName').val(book.name);
            $('#floatingBookType').val(book.book_type);

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
        })
        .catch(e => {
            utils.showAlert('Ошибка при загрузке данных книги');
        });
}

async function getBookData(bookId) {
    return await window.axios.get(`/api/v1/books/${bookId}`);
}

async function deleteBook(bookId) {
    return await window.axios.delete(`/books/${bookId}`);
}
