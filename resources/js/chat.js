import './echo.js';

let userId = $('.chat-container').data('user');
let recipientId;
let csrf_token = $('input[name="_token"]').val();
let $messagesContainer = $('.messages-container');
let unreadMessages = [];
let readMessages = [];
let counter = 0;

$(function () {
    // TODO: получить все непрочитанные сообщения юзера и поставить маркеры на соответствующих диалогах
    unreadMessagesRequest(userId).then(function (response) {
        if (response.success) {
            if (response.messages.length > 0) {
                response.messages.forEach(function (message) {
                    unreadMessages.push(message.id);
                });

                response.messages.forEach(function (message) {
                    let obj = $(`.chat-row[data-recipient="${message.from_id}"]`);
                    if (obj.length > 0) {
                        obj.children('.new-message-icon').show();
                    }
                    return;
                });
            }
        }
    });

    let queryRecipient = $('#query-recipient').val();
    if (queryRecipient !== '') {
        let obj = $(`.chat-row[data-recipient="${queryRecipient}"]`);
        if (obj.length > 0) {
            recipientId = obj.data('recipient');
            selectChat(obj);
        }
    }

    $('.chat-row ').on('click', function () {
        recipientId = $(this).data('recipient');
        selectChat($(this));
    });
    if (userId) {
        Echo.private(`user.${userId}`)
            .listen('MessageSent', (socketMessage) => {
                let obj = $(`.chat-row[data-recipient="${socketMessage.message.from_id}"]`);
                if (obj.length > 0) {
                    if (obj.hasClass('active')) {
                        appendMessage(socketMessage.message);
                        // TODO: текст внизу "непрочитанных сообщений"
                    }

                    unreadMessages.push(socketMessage.message.id);

                    obj.children('.new-message-icon').show();
                    $($messagesContainer).trigger('scroll');
                }
                // TODO: показать уведомление
                //  отправлять два сообщения: в канал уведомлений и в канал чата ??
            });
    }

    $($messagesContainer).on('scroll', function () {

        unreadMessages.forEach(function (unreadMessageId, key) {
            let $unreadMessageDiv = $(`div[id="message-${unreadMessageId}"]`);
            if ($unreadMessageDiv.length > 0 && $unreadMessageDiv.isInDiv()) {
                $('.chat-row.active').children('.new-message-icon').hide();
                readMessages.push(unreadMessageId);
                delete (unreadMessages[key]);
            }
        });

        if (readMessages.length > 0) {
            markAsReadRequest(readMessages).then(function (response) {
                console.log(response);
                if (response.success) {
                    counter = 0;
                }
            });
        }
    });

    $('#sendMessageBtn').on('click', function () {
        let textInput = $('#messageInput');
        let text = textInput.val();
        textInput.val('');
        sendMessage(text).then(function (response) {
            if (response.success) {
                appendMessage(response.message);

                $messagesContainer.animate({
                    scrollTop: $messagesContainer.prop('scrollHeight')
                }, 550);
            }
        });
    });
});

function selectChat($node) {
    if (!$node.hasClass('active')) {
        madeActive($node);
        // $node.children('.new-message-icon').hide();

        messagesListRequest(userId)
            .then(function (response) {
                if (response.success) {
                    displayChat(response);

                    // $messagesContainer.animate({
                    //     scrollTop: 0
                    // }, 550);
                }
            });
    }
}

function madeActive($node) {
    $('.chat-row').each(function () {
        $(this).removeClass('bg-dark-subtle active');
    });
    if (!$node.hasClass('bg-dark-subtle active')) {
        $node.toggleClass('bg-dark-subtle active');
    }
}

async function messagesListRequest(userId) {
    return await $.ajax({
        url: `/api/v1/users/${userId}/chat/${recipientId}`,
        type: 'get',
        async: true
    });
}

function displayChat(response) {

    displayTypingArea();

    displayChatHeader(response.recipient);

    displayMessages(response.messages, response.recipient);
}

function displayTypingArea() {
    $('.no-messages-text').toggleClass('d-none');
    $('.typing-area').toggleClass('d-none');
}

function displayChatHeader(recipient) {
    $('.chat-header p').text('Чат с ' + recipient.name);
}

function displayMessages(messages) {
    for (const [date, content] of Object.entries(messages)) {
        let dateObj = new Date(date);
        let options = {
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        let formattedDate = dateObj.toLocaleDateString('ru-RU', options);

        appendDateSection(formattedDate);
        content.forEach(function (message) {
            appendMessage(message);
        });
    }
}

function appendDateSection(date) {
    $messagesContainer.append(`<p class="p-0 pb-3 m-auto text-secondary">${date}</p>`);
}

function appendMessage(message) {
    let created_at = new Date(message.created_at);
    let time = created_at.getHours() + ':' + (created_at.getMinutes() < 10 ? '0' + created_at.getMinutes() : created_at.getMinutes());

    let $messageDiv = $('<div>').attr('id', 'message-' + message.id).addClass('message-div rounded-3 px-2 py-1 mx-2 my-1').css('max-width', '45%');
    let $messageBody = $('<p>').addClass('message-body p-0 m-0 pe-4').text(message.body);
    let $messageTime = $('<p>').addClass('message-date p-0 m-0 small text-secondary text-end').text(time);

    let divClasses = ['bg-none', 'border', 'border-black', 'text-start', 'me-auto'];

    if (message.from_id === userId) {
        divClasses = ['bg-dark-subtle', 'text-end', 'ms-auto'];
    }

    divClasses.forEach(function (className) {
        $messageDiv.addClass(className);
    });

    $messageDiv.append($messageBody);
    $messageDiv.append($messageTime);

    $messagesContainer.append($messageDiv);
    counter++;
}

async function sendMessage(text) {
    return await $.ajax({
        url: `/api/v1/users/${userId}/chat/${recipientId}/message`,
        type: 'post',
        async: true,
        data: {
            body: text,
            _token: csrf_token
        }
    });
}

$.fn.isInDiv = function () {
    if (this.length === 0) return false;

    const element = $(this)[0];
    const containerEl = $(this).parent()[0];

    const containerRect = containerEl.getBoundingClientRect();
    const elementRect = element.getBoundingClientRect();

    return (
        elementRect.bottom > containerRect.top &&
        elementRect.top < containerRect.bottom &&
        elementRect.right > containerRect.left &&
        elementRect.left < containerRect.right
    );
};

async function unreadMessagesRequest(userId) {
    return await $.ajax({
        url: `/api/v1/users/${userId}/messages`,
        type: 'get',
        async: true
    });
}

async function markAsReadRequest(messages) {
    return await $.ajax({
        url: `/api/v1/users/${userId}/messages/read`,
        type: 'patch',
        async: true,
        data: {
            messages: messages,
            _token: csrf_token
        }
    });
}
