import * as utils from "./utils.js";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

let token = $('input[name="_token"]').val();
let userId;
let marked = [];

userId = $('#notifications-container').data('user');
$('.notification-div').on('click', () => {
    let notification = $(this).data('notification');
    let $this = $(this);

    let hiddenDiv = $(this).children('.hidden-div').first();
    if (hiddenDiv.is(':visible')) {
        hiddenDiv.slideUp('fast');
        checkOneRequest(notification)
            .then(response => {
                $this.slideUp('fast', () => {
                    $(this).remove();
                });
            })
            .catch(e => {
                utils.showAlert('Ошибка при обработке уведомления');
            });
    } else {
        hiddenDiv.slideDown('fast')
    }
});

$('#check-all-div').on('click', () => {
    if ($(this).attr('role') === 'button') {
        checkAllRequest()
            .then(response => {
                utils.showAlert('success', 'Уведомления отмечены прочитанными')
                $('#notifications-container').fadeOut(500, () => {
                    $(this).empty().show();
                });
            })
            .catch(e => {
                utils.showAlert('Ошибка при обработке уведомления');
            });
    }
});

// TODO:  почему то срабатывает со старта
$(window).on('beforeunload', () => {
    $('.hidden-div :visible').each(() => {
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
