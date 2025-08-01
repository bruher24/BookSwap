$(function () {
    $('#chat_btn').on('click', function (e) {
        if (!$(this).data('user')) {
            e.preventDefault();
            const modalLogin = new bootstrap.Modal('#modalLogin');
            modalLogin.show();
        }
    });
});
