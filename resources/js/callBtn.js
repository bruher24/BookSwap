import * as utils from "./utils.js";

$(function () {
    $('#call_btn').on('click', function () {
        if ($(this).data('user')) {
            let $hiddenNumber = $('#hidden-number');
            $hiddenNumber.fadeToggle(100, function () {
            });
            $hiddenNumber.toggleClass('d-none');
        } else {
            const modalLogin = new bootstrap.Modal('#modalLogin')
            modalLogin.show();
        }
    });

    $('.to-copy').on('click', async function () {
        await utils.showFadeAlert($(this).data('copy'), 'Номер скопирован!');
    });
});
