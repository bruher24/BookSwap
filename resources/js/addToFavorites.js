$(function () {
    $('#like_btn').on('click', function () {
        $(this).toggleClass('liked');

        if ($(this).hasClass('liked')) {
            localStorage.setItem('book_liked', 'true');
        } else {
            localStorage.removeItem('book_liked');
        }
    });

    if (localStorage.getItem('book_liked') === 'true') {
        $('#like_btn').addClass('liked');
    }
});