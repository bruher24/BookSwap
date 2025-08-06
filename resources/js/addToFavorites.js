let form = $('#favorites-form');
$('.like-btn').on('click', function () {
    if ($(this).data('user')) {
        $(this).toggleClass('liked');
        form[0].submit();
    } else {
        const modalLogin = new bootstrap.Modal('#modalLogin');
        modalLogin.show();
    }
});
