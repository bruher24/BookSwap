
    $('.book-card').on('contextmenu', function (e) {
        e.preventDefault();

        $('.custom-context-menu').remove();
        const bookId = $(this).data('book');
        const menu = $(
            `<ul class="custom-context-menu dropdown-menu show position-fixed" 
                style="display: block; z-index: 1000;">
                <li><a class="dropdown-item" href="">Поделиться</a></li>
                <li><button id="editBookBtn" class="bookBtn link dropdown-item" data-bs-toggle="modal"
                    data-bs-target="#modalBookForm">Изменить книгу</button></li>
                <li><a class="dropdown-item link-danger" href="/books/${bookId}/delete">Удалить книгу</a></li>
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

        $('#modalBookForm').on('show.bs.modal', function () {
            menu.remove();
            $(document).off('click.contextmenu');
            fillBookData(bookId);
        });
    });


function fillBookData(bookId) {
    getBookData(bookId).then(function(bookData) {
        const book = bookData.data.book;
        console.log(book);
        $('#floatingInput').val(book.name);
    });
}

async function getBookData(bookId) {
    try{
        const reponse = await $.ajax({
            url: `/books/${bookId}`,
            type: 'get',
            async: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        });
        return {
            success: true,
            data: reponse
        };
    } catch (error) {
        console.error('Ошибка:', error);
        return {
            success: false,
            errors: error.responseJSON?.errors || {error: [error.responseJSON?.message || 'Произошла ошибка']}
        };
    }
}