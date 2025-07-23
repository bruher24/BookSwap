$(function () {
    let form = $('#favorites-form');
    $('.like_btn').on('click', function () {
        if ($(form.data('user')) !== null) {
            $(this).toggleClass('liked');
            form[0].submit();
        } else {
            const modalLogin = new bootstrap.Modal('#modalLogin')
            modalLogin.show();
        }
    });
});
