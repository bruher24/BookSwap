$(function () {
    $('#call_btn').on('click', function () {
        console.log($(this).data('user'))
        if ($(this).data('user')) {
            // TODO: show number
        } else {
            const modalLogin = new bootstrap.Modal('#modalLogin')
            modalLogin.show();
        }
    });
});