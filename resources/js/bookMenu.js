$(function () {
    $('.book-card').on('contextmenu', function(e) {
        e.preventDefault();

        $('.custom-context-menu').remove();
        const bookId = $(this).data('book');
        const menu = $(
            `<ul class="custom-context-menu dropdown-menu show position-fixed" 
                style="display: block; z-index: 1000;">
                <li><a class="dropdown-item" href="">Поделиться</a></li>
                <li><a class="dropdown-item" href="#">Изменить книгу</a></li>
                <li><a class="dropdown-item link-danger" href="/books/${bookId}/delete">Удалить книгу</a></li>
            </ul>`
        );

        menu.css({
            'left': e.pageX + 'px',
            'top': e.pageY + 'px'
        });

        $('body').append(menu);

        $(document).on('click.contextmenu', function(e) {
            if (!$(e.target).closest('.custom-context-menu').length) {
                menu.remove();
                $(document).off('click.contextmenu');
            }
        });
    });
});