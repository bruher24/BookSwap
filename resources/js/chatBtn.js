$(function () {
    $('#chat_btn').on('click', function (e) {
        console.log($(this).data('user'))
        if (!$(this).data('user')) {
            e.preventDefault();
            const modalLogin = new bootstrap.Modal('#modalLogin')
            modalLogin.show();
        }
    });
});