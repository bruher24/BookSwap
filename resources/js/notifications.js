import * as utils from "./utils.js";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

let userId;
let marked = [];

userId = $('#notifications-container').data('user');
$('.notification-div').on('click', function () {
    let notification = $(this).data('notification');
    let $this = $(this);

    let hiddenDiv = $(this).children('.hidden-div').first();
    if (hiddenDiv.is(':visible')) {
        hiddenDiv.slideUp('fast');
        checkOneRequest(notification)
            .then(response => {
                $this.slideUp('fast', function () {
                    $(this).remove();
                });
            })
            .catch(e => {
                if (e.response.data.message) {
                    utils.showAlert('danger', e.response.data.message);
                }
            });
    } else {
        hiddenDiv.slideDown('fast')
    }
});

$('#check-all-div').on('click', function () {
    if ($(this).attr('role') === 'button') {
        checkAllRequest()
            .then(response => {
                utils.showAlert('success', 'Уведомления отмечены прочитанными')
                $('#notifications-container').fadeOut(500, function () {
                    $(this).empty().show();
                });
            })
            .catch(e => {
                if (e.response.data.message) {
                    utils.showAlert('danger', e.response.data.message);
                }
            });
    }
});

// TODO:  почему то срабатывает со старта
$(window).on('beforeunload', () => {
    $('.hidden-div :visible').each(function () {
        let id = $(this).parent().data('notification');
        marked.push(id);
    });
    console.log(marked);
    marked = ['1', '2'];

    checkManyRequest(marked).then(response => {
        console.log(response);
    });
});

async function checkOneRequest(notification) {
    return await window.axios.patch(`/api/v1/users/${userId}/notifications/${notification}`);
}

async function checkAllRequest() {
    return await window.axios.patch(`/api/v1/users/${userId}/notifications/check-all`);
}

async function checkManyRequest(marked) {
    return await window.axios.patch(`/api/v1/users/${userId}/notifications/check-many`, {
        notifications: marked
    });
}
