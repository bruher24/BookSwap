import {showAlert} from "./utils.js";

let token = $('input[name="_token"]').val();
let userId;
let marked = [];
$(function () {
    userId = $('#notifications-container').data('user');
    $('.notification-div').on('click', function () {
        let notification = $(this).data('notification');
        let $this = $(this);

        let hiddenDiv = $(this).children('.hidden-div').first();
        if (hiddenDiv.is(':visible')) {
            hiddenDiv.slideUp('fast');
            checkOneRequest(notification).then(function (response) {
                if (response.success) {
                    $this.slideUp('fast', function () {
                        $(this).remove();
                    });
                }
            });
        } else {
            hiddenDiv.slideDown('fast')
        }
    });

    $('#check-all-div').on('click', function () {
        if ($(this).attr('role') === 'button') {
            checkAllRequest().then(function (response) {
                if (response.success) {
                    showAlert('success', 'Уведомления отмечены прочитанными')
                    $('#notifications-container').fadeOut(500, function () {
                        $(this).empty().show();
                    });
                }
            });
        }
    });
});

// TODO:  почему то срабатывает со старта
$(window).on('beforeunload', function () {
    $('.hidden-div :visible').each(function () {
        let id = $(this).parent().data('notification');
        marked.push(id);
    });
    console.log(marked);
    marked = ['1', '2'];

    checkManyRequest(marked).then(function (response) {
        console.log(response);
    });
});

async function checkOneRequest(notification) {
    return await $.ajax({
        url: `/api/v1/users/${userId}/notifications/${notification}`,
        type: 'patch',
        async: true,
        data: {
            _token: token
        }
    });
}

async function checkAllRequest() {
    return await $.ajax({
        url: `/api/v1/users/${userId}/notifications/check-all`,
        type: 'patch',
        async: true,
        data: {
            _token: token
        }
    });
}

async function checkManyRequest(marked) {
    let response = $.ajax({
        url: `/api/v1/users/${userId}/notifications/check-many`,
        type: 'patch',
        async: true,
        data: {
            _token: token,
            notifications: marked
        }
    });

    console.log(response);
    return await response;
}
